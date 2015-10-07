<?php

namespace Modules\Articles\Jobs;

use DB;
use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;

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
        if ($this->user->account->balance < $this->article->getCost()) {
            throw new NotEnoughMoneyException;
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