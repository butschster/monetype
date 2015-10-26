<?php

namespace Modules\Users\Model;

use Modules\Articles\Model\Article;
use Modules\Articles\Model\Tag;
use Modules\Support\Helpers\String;
use Illuminate\Auth\Authenticatable;
use Modules\Users\Traits\AvatarTrait;
use Modules\Transactions\Model\Account;
use Illuminate\Database\Eloquent\Model;
use Modules\Articles\Model\ArticleCheck;
use Modules\Users\Traits\BackgroundTrait;
use Modules\Users\Traits\PermissionsTrait;
use Modules\Transactions\Model\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
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
 * @property string         $background
 * @property float          $balance
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Account        $account
 * @property Collection     $followers
 * @property Collection     $favorites
 * @property Collection     $checks
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes,
        AvatarTrait, BackgroundTrait, PermissionsTrait;

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
        'username',
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
     * @param integer|float $amount
     *
     * @return bool
     */
    public function hasMoney($amount)
    {
        return $this->account->balance >= $amount;
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checks()
    {
        return $this->hasMany(ArticleCheck::class, 'user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(Article::class, 'user_favorites');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'user_tags');
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


    /**
     * @return User
     */
    public static function getDebitUser()
    {
        return static::findOrFail(Transaction::ACCOUNT_DEBIT);
    }


    /**
     * @return User
     */
    public static function getCreditUser()
    {
        return static::findOrFail(Transaction::ACCOUNT_CREDIT);
    }
}
