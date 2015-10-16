<?php

namespace Modules\Articles\Jobs;

use DB;
use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

class PurchaseArticle implements SelfHandling
{

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var User
     */
    protected $user;


    /**
     * @param Article $article
     * @param User    $user
     */
    public function __construct(Article $article, User $user)
    {
        $this->article = $article;
        $this->user    = $user;
    }


    /**
     * @return Transaction
     * @throws NotEnoughMoneyException
     */
    public function handle()
    {
        if ($this->article->isFree()) {
            return true;
        }

        if ($this->article->authoredBy($this->user) or $this->article->isPurchasedByUser($this->user)) {
            return true;
        }

        if ( ! $this->user->hasMoney($this->article->getCost())) {
            throw new NotEnoughMoneyException(trans('articles::article.message.not_enough_money'));
        }

        return DB::transaction(function () {
            $transaction         = new Transaction;
            $transaction->amount = $this->article->getCost();

            $transaction->assignPurchaser($this->user);
            $transaction->assignRecipient($this->article->author);
            $transaction->setType('payment');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');
            $transaction->assignArticle($this->article);

            $transaction->save();

            $transaction->complete(function (Transaction $t) {
                $this->article->count_payments += 1;
                $this->article->amount += $t->amount;
                $this->article->save();
            });

            return $transaction;
        });
    }
}