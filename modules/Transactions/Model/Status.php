<?php

namespace Modules\Transactions\Model;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction_statuses';

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
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'type_id');
    }
}