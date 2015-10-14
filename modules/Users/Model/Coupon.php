<?php

namespace Modules\Users\Model;

use Carbon\Carbon;
use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $from_user_id
 * @property integer $to_user_id
 * @property string  $code
 * @property float   $amount
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
     */
    public function assignFromUser(User $user)
    {
        $this->fromUser()->associate($user);
    }


    /**
     * @param User $user
     */
    public function assignToUser(User $user)
    {
        $this->toUser()->associate($user);
    }


    /**
     * @return bool
     */
    public function isExpired()
    {
        return ($this->expired_at instanceof Carbon) and $this->expired_at->lt(Carbon::now());
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
}