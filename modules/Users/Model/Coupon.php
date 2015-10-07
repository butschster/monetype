<?php

namespace Modules\Users\Model;

use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer        $id
 * @property string         $code
 * @property float          $amount
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $expired_at
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
    protected $dates = [ 'expired_at', 'deleted_at' ];
}