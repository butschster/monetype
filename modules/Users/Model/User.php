<?php

namespace Modules\Users\Model;

use HTML;
use Image;
use Modules\Support\Helpers\String;
use Illuminate\Auth\Authenticatable;
use Modules\Support\Helpers\Gravatar;
use Modules\Transactions\Model\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * @property integer        $id
 * @property string         $name
 * @property string         $username
 * @property string         $email
 * @property string         $gender
 * @property string         $status
 * @property string         $avatar
 * @property float          $balance
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Collection     $roles
 * @property Account        $account
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    const STATUS_NEW = 'new';
    const STATUS_APPROVED = 'approved';
    const STATUS_BLOCKED = 'blocked';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }


    /**
     * @return bool
     */
    public function isModerator()
    {
        return $this->hasRole(Role::ROLE_MODERATOR);
    }


    /**
     * @return bool
     */
    public function isBlocked()
    {
        return $this->status === static::STATUS_BLOCKED;
    }



    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return float
     */
    public function getBalance()
    {
        return String::formatAmount($this->account->balance);
    }


    /**
     * @param array $attributes
     *
     * @return string
     */
    public function getProfileLink($title = null, array $attributes = [])
    {
        if (is_null($title)) {
            $title = $this->getName();
        }

        if ( ! empty( $this->username )) {
            return link_to_route('front.profile.showByUsername', $title, $this->username, $attributes);
        } else {
            return link_to_route('front.profile.showById', $title, $this->id, $attributes);
        }
    }

    /**********************************************************************
     * Avatar
     **********************************************************************/

    /**
     * @param int $size
     *
     * @return string
     */
    public function getAvatar($size = 50)
    {
        if ( ! empty($this->avatar)) {
            return $this->getAvatarHtml(['width' => $size . 'px', 'class' => 'img-circle']);
        }

        return $this->getGravatarHTML($size);
    }


    /**
     * @param array $attributes
     *
     * @return string
     */
    public function getAvatarHtml(array $attributes = [])
    {
        return HTML::image("avatars/{$this->avatar}", $this->getName(), $attributes);
    }


    /**
     * @param int $size
     *
     * @return string
     */
    public function getGravatarHTML($size = 50)
    {
        return Gravatar::load($this->email, $size, null, ['class' => 'img-circle']);
    }


    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function attachAvatar(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName  = uniqid() . '.' . $extension;
        $path      = $this->getPhotoDitectory();

        if ($file->move($path, $fileName)) {
            $this->deletePhoto();
            $image = Image::make($path . $fileName);

            $image->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->crop(200, 200);

            $image->orientate();

            $image->save(null, 100);

            $this->avatar = $fileName;
            $this->save();

            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function deletePhoto()
    {
        if ( ! is_null($this->avatar) and file_exists($oldPhoto = $this->getPhotoDitectory() . $this->avatar)) {
            @unlink($oldPhoto);
            $this->avatar = null;

            $this->save();

            return true;
        }

        return false;
    }


    /**
     * @return string
     */
    public function getPhotoDitectory()
    {
        return public_path('avatars' . DIRECTORY_SEPARATOR);
    }

    /**********************************************************************
     * Permissions
     **********************************************************************/

    /**
     * Assign the given role to the user.
     *
     * @param  string $role
     *
     * @return mixed
     */
    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }


    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role) && strpos($role, '|') !== false) {
            return ! ! count(
                array_intersect(explode('|', $role), $this->roles->lists('name')->all())
            );
        }

        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return ! ! $role->intersect($this->roles)->count();
    }


    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     *
     * @return boolean
     */
    public function hasPermission(Permission $permission)
    {
        return $this->isAdmin() or $this->hasRole($permission->roles);
    }

    /**********************************************************************
     * Mutators
     **********************************************************************/

    /**
     * @return string
     */
    public function getBalanceAttribute()
    {
        return $this->getBalance();
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }


    /**
     * A user may have multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }


    /**********************************************************************
     * Static methods
     **********************************************************************/

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->remember_token = str_random(10);
        });

        static::created(function (User $user) {
            $user->account()->create([]);
        });
    }
}
