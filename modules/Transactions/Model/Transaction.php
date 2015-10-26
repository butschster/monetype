<?php

namespace Modules\Transactions\Model;

use Closure;
use Modules\Users\Model\User;
use Modules\Support\Helpers\Date;
use Modules\Articles\Model\Article;
use Modules\Support\Helpers\String;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer        $id
 * @property integer        $type_id
 * @property integer        $status_id
 * @property integer        $payment_method_id
 * @property integer        $article_id
 * @property float          $amount
 * @property float          $commission
 * @property string         $details
 * @property string         $created
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
        'details',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
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
    public function isCompleted()
    {
        return $this->status_id == 'completed';
    }


    /**
     * @return bool
     */
    public function isCanceled()
    {
        return $this->status_id == 'canceled';
    }


    /**
     * @param Closure|null $callback
     *
     * @return bool
     */
    public function complete(Closure $callback = null)
    {
        if ($this->isCanceled()) {
            return false;
        }

        $amount     = $this->amount;
        $commission = $this->type->calculateComission($amount);

        if ($commission > 0) {
            $this->comission = $commission;
        }

        $this->creditAccount->addMoney($this->amount);
        $this->debitAccount->withdrawMoney($this->amount);

        $this->setStatus('completed')->save();

        if (is_callable($callback)) {
            $callback($this);
        }

        return true;
    }


    /**
     * @param Closure|null $callback
     * @param array|null   $details
     *
     * @return bool
     */
    public function cancel(Closure $callback = null, array $details = null)
    {
        if ($this->isCanceled()) {
            return false;
        }

        if ($this->isCompleted()) {
            $this->debitAccount->addMoney($this->amount);
            $this->creditAccount->withdrawMoney($this->amount);
        }

        $this->details = $details;
        $this->setStatus('canceled')->save();

        if (is_callable($callback)) {
            $callback($this);
        }

        return true;
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
     * Mutators
     **********************************************************************/

    /**
     * @return string|null
     */
    public function getCreatedAttribute()
    {
        return Date::format($this->created_at);
    }


    /**
     * @return string
     */
    public function getAmountAttribute()
    {
        return String::formatAmount(array_get($this->attributes, 'amount'));
    }


    /**********************************************************************
     * Scopes
     **********************************************************************/

    /**
     * @param Builder  $query
     * @param User|int $userId
     *
     * @return $this
     */
    public function scopeByUser(Builder $query, $userId)
    {
        if ($userId instanceof User) {
            $userId = $userId->getKey();
        }

        return $query->where('debit', $userId);
    }


    /**
     * @param Builder     $query
     * @param Article|int $articleId
     *
     * @return $this
     */
    public function scopeByArticle(Builder $query, $articleId)
    {
        if ($articleId instanceof Article) {
            $articleId = $articleId->getKey();
        }

        return $query->where('article_id', $articleId);
    }


    /**
     * @param Builder $query
     *
     * @return $this
     */
    public function scopeOnlyPayments(Builder $query)
    {
        return $query->where('type_id', 'payment');
    }


    /**
     * @param Builder $query
     *
     * @return $this
     */
    public function scopeOnlyCompleted(Builder $query)
    {
        return $query->where('status_id', 'completed');
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