<?php

namespace Modules\Transactions\Model;

use Closure;
use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer        $id
 * @property integer        $type_id
 * @property integer        $status_id
 * @property integer        $payment_method_id
 * @property integer        $article_id
 * @property float          $amount
 * @property float          $comission
 * @property string         $details
 *
 * @property Article        $article
 * @property Type           $type
 * @property Status         $status
 * @property PaymentMethod  $paymentMethod
 * @property User           $debitAccount
 * @property User           $creditAccount
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Transaction extends Model
{

    use SoftDeletes;

    const ACCOUNT_CREDIT = 2;
    const ACCOUNT_DEBIT = 3;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'details'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];


    /**
     * @param Closure $callback
     */
    public function complete(Closure $callback = null)
    {
        $amount    = $this->amount;
        $comission = $this->type->calculateComission($amount);

        if ($comission > 0) {
            $this->comission = $comission;
        }

        $debitAccount = $this->debitAccount->account;
        $debitAccount->balance -= $amount;
        $debitAccount->save();

        $creditAccount = $this->creditAccount->account;
        $creditAccount->balance += $amount;
        $creditAccount->save();

        $this->setStatus('completed')->save();

        if (is_callable($callback)) {
            $callback($this);
        }
    }


    /**
     * @param string|Type $type
     *
     * @return $this
     */
    public function setType($type)
    {
        if (is_string($type)) {
            $type = Type::find($type);
        }

        return $this->type()->associate($type);
    }


    /**
     * @param string|Status $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if (is_string($status)) {
            $status = Status::find($status);
        }

        return $this->status()->associate($status);
    }


    /**
     * @param string|PaymentMethod $method
     *
     * @return $this
     */
    public function setPaymentMethod($method)
    {
        if (is_string($method)) {
            $method = PaymentMethod::find($method);
        }

        return $this->paymentMethod()->associate($method);
    }


    /**
     * @param User $user
     *
     * @return $this
     */
    public function assignRecipient(User $user)
    {
        return $this->creditAccount()->associate($user);
    }


    /**
     * @param User $user
     *
     * @return $this
     */
    public function assignPurchaser(User $user)
    {
        return $this->debitAccount()->associate($user);
    }


    /**
     * @param Article $article
     *
     * @return $this
     */
    public function assignArticle(Article $article)
    {
        return $this->article()->associate($article);
    }

    /**********************************************************************
     * Relations
     **********************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function debitAccount()
    {
        return $this->belongsTo(User::class, 'debit');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creditAccount()
    {
        return $this->belongsTo(User::class, 'credit');
    }
}