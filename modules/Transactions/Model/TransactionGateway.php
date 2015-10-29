<?php

namespace Modules\Transactions\Model;

use Modules\Users\Model\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer        $id
 * @property string         $status
 * @property integer        $user_id
 * @property integer        $transaction_id
 * @property float          $amount
 * @property string         $payment_id
 *
 *
 * @property User           $user
 * @property Transaction    $transaction
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
abstract class TransactionGateway extends Model
{

    const STATUS_NEW = 'new'; // Новая
    const STATUS_COMPLETED = 'completed'; // Проведена
    const STATUS_CANCELED = 'canceled'; // Отклонено

    protected static function boot()
    {
        parent::boot();

        static::creating(function (TransactionGateway $t) {
            $transaction = new Transaction;
            $transaction->amount = $t->amount;

            $transaction->assignPurchaser(User::getDebitUser());
            $transaction->assignRecipient($t->user);
            $transaction->setPaymentMethod($t->getPaymentMethod());
            $transaction->setType(Transaction::TYPE_CASHIN);
            $transaction->save();

            $t->transaction()->associate($transaction);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
    ];


    /**
     * @return string
     */
    abstract public function getPaymentMethod();


    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->status == static::STATUS_NEW;
    }


    /**
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status == static::STATUS_COMPLETED;
    }


    /**
     * @return bool
     */
    public function isCanceled()
    {
        return $this->status == static::STATUS_CANCELED;
    }


    /**
     * @return bool
     */
    public function complete()
    {
        if ( ! $this->isNew()) {
            return false;
        }

        $this->transaction->complete(function() {
            $this->status = static::STATUS_COMPLETED;
            $this->save();
        });

        return true;
    }


    /**
     * @return bool
     */
    public function cancel()
    {
        if ($this->isCanceled()) {
            return false;
        }

        $this->transaction->cancel(function() {
            $this->status = static::STATUS_CANCELED;
            $this->save();
        });

        return true;
    }


    /**
     * @param User $user
     *
     * @return $this
     */
    public function assignRecipient(User $user)
    {
        return $this->user()->associate($user);
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}