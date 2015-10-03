<?php

namespace Modules\Transactions\Model;

use Modules\Articles\Model\Article;
use Modules\Users\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use SoftDeletes;

    const DEFAULT_ACCOUNT = 1;

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
    protected $dates = ['deleted_at'];

    /**
     * @param string|Type $type
     * @return $this
     */
    public function setType($type)
    {
        return $this->type()->associate($type);
    }


    /**
     * @param string|Status $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->status()->associate($status);
    }


    /**
     * @param string|PaymentMethod $method
     * @return $this
     */
    public function setPaymentMethod($method)
    {
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