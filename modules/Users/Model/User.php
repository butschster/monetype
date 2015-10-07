<?php

namespace Modules\Users\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Transactions\Model\Account;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * @property integer        $id
 * @property string         $name
 * @property string         $email
 * @property string         $gender
 * @property string         $status
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Collection     $roles
 * @property Account        $account
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword;

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
        'gender'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];


    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }


    /**
     * Determine if the user has the given role.
     *
     * @param  mixed $role
     *
     * @return boolean
     */

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
            $user->account()
                 ->create([]);
        });
    }
}
