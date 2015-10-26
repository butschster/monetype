<?php

namespace Modules\Articles\Jobs;

use Modules\Articles\Model\Article;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

class CancelArticlePurchases implements SelfHandling
{

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var array|null
     */
    protected $details;


    /**
     * @param Article    $article
     * @param array|null $details
     */
    public function __construct(Article $article, array $details = null)
    {
        $this->article = $article;
        $this->details = $details;
    }


    /**
     * @return Transaction
     * @throws NotEnoughMoneyException
     */
    public function handle()
    {
        foreach ($this->article->purchases()->onlyCompleted()->get() as $purchase) {
            $purchase->cancel(null, $this->details);
        }
    }
}