<?php

namespace Modules\Users\Model;

use Carbon\Carbon;
use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string  $code
 * @property float   $amount
 *
 * @property User    $user
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
    public function assignUser(User $user)
    {
        $this->user()->associate($user);
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}