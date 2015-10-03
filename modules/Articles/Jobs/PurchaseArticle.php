<?php

namespace Modules\Articles\Model;

use DB;
use Modules\Users\Model\User;
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


    public function handle()
    {
        return DB::transaction(function () {
            $transaction = new Transaction;
            $transaction->amount = $this->article->getCost();

            $transaction->assignPurchaser($this->user);
            $transaction->assignRecipient($this->article->author);
            $transaction->setType('payment');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');
            $transaction->assignArticle($this->article);

            $transaction->save();

            $this->article->count_payments += 1;
            $this->article->amount += $transaction->amount;
            $this->article->save();

            $transaction->setStatus('completed')->save();

            return $transaction;
        });
    }
}