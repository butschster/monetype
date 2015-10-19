<?php

namespace Modules\Users\Model;

use Carbon\Carbon;
use Modules\Support\Helpers\Date;
use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $from_user_id
 * @property integer $to_user_id
 * @property string  $code
 * @property float   $amount
 * @property float   $formatedAmount
 * @property string  $expired
 *
 * @property User    $fromUser
 * @property User    $toUser
 *
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $expired_at
 */
class Coupon extends Model
{

    use SoftDeletes;


    protected static function boot()
    {
        parent::boot();

        static::creating(function (Coupon $coupon) {
            $coupon->code = String::uniqueId();
        });
    }


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expired_at', 'deleted_at'];


    /**
     * @param User $user
     *
     * @return User
     */
    public function assignFromUser(User $user)
    {
        return $this->fromUser()->associate($user);
    }


    /**
     * @param User $user
     *
     * @return User
     */
    public function assignToUser(User $user)
    {
        return $this->toUser()->associate($user);
    }


    /**
     * @return bool
     */
    public function isExpired()
    {
        return ($this->expired_at instanceof Carbon) and $this->expired_at->lt(Carbon::now());
    }


    /**
     * @param string|Type $type
     *
     * @return $this
     */
    public function setType($type)
    {
        if (is_string($type)) {
            $type = CouponType::find($type);
        }

        return $this->type()->associate($type);
    }

    /**********************************************************************
     * Mutators
     **********************************************************************/

    /**
     * @return string
     */
    public function getFormatedAmountAttribute()
    {
        return String::formatAmount($this->amount);
    }


    /**
     * @return string
     */
    public function getExpiredAttribute()
    {
        return Date::format($this->expired_at, 'd F Y');
    }

    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param Builder $query
     * @param integer $userId
     *
     * @return Builder
     */
    public function scopeFilterByUser(Builder $query, $userId)
    {
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        return $query->where('from_user_id', $userId);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyActive(Builder $query)
    {
        return $query->where(function($q) {
            return $q->orWhereNull('expired_at')->orWhereRaw('expired_at > CURDATE()');
        });
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyUsers(Builder $query)
    {
        return $query->where('type_id', 'user');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyForRegister(Builder $query)
    {
        return $query
            ->onlyActive()
            ->where('type_id', 'register')
            ->whereNull('to_user_id');
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(CouponType::class, 'type_id');
    }
}