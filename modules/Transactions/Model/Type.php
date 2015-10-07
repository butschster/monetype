<?php

namespace Modules\Transactions\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string     $name
 * @property string     $title
 * @property string     $description
 * @property float      $comission
 * @property integer    $comission_percent
 *
 * @property Collection $transactions
 */
class Type extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction_types';

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
        'description',
    ];


    /**
     * @param integer|float $amount
     *
     * @return float
     */
    public function calculateComission($amount)
    {
        floatval($amount);

        if ($this->comission_percent > 0) {
            return ( $this->comission_percent / 100 ) * $amount;
        }

        return $this->comission;
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'type_id');
    }
}