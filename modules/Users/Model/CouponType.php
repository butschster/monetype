<?php

namespace Modules\Users\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string     $name
 * @property string     $title
 *
 * @property Collection $coupons
 */
class CouponType extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupon_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'type_id');
    }
}