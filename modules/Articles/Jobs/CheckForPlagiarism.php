<?php

namespace Modules\Articles\Jobs;

use DB;
use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Modules\Articles\Model\ArticleCheck;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;
use Modules\Articles\Exceptions\CheckForPlagiarismException;

class CheckForPlagiarism implements SelfHandling
{

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var string
     */
    protected $key = 'WJghphcU0oAofaR';

    /**
     * @var array
     */
    protected $response;


    /**
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }


    public function handle()
    {
        $checkingCost = (int) config('article.check.cost', 0);

        if($checkingCost > 0) {
            $this->payAndSendRequest($checkingCost);
        } else {
            $this->sendRequest();
        }

        if (empty( $response = $this->response )) {
            throw new CheckForPlagiarismException();
        }

        $check = new ArticleCheck;

        $check->article()->associate($this->article);
        $check->percent = array_get($response, 'percent');
        $check->text    = array_get($response, 'text');
        $check->error   = array_get($response, 'error');

        unset( $response['percent'], $response['text'], $response['error'] );

        $check->response = $response;

        $check->save();

        $this->article->setChecked();

        return $check;
    }


    /**
     * @return array
     * @throws CheckForPlagiarismException
     */
    protected function sendRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'text' => $this->article->text,
            'key'  => $this->key,
            'test' => config('article.check.test') === false ? 0 : 1,
        ]);

        curl_setopt($curl, CURLOPT_URL, 'http://www.content-watch.ru/public/api/');
        $response = json_decode(trim(curl_exec($curl)), true);
        curl_close($curl);

        if ( ! empty( $response['error'] )) {
            throw new CheckForPlagiarismException($response['error']);
        }

        return $this->response = $response;
    }


    /**
     * @param float|integer $amount
     *
     * @return mixed
     */
    protected function payAndSendRequest($amount)
    {
        return DB::transaction(function () use ($amount) {
            $transaction         = new Transaction();
            $transaction->amount = $amount;

            $transaction->assignPurchaser($this->article->author);
            $transaction->assignRecipient(User::find(Transaction::ACCOUNT_DEBIT));
            $transaction->setType('article_check');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');
            $transaction->assignArticle($this->article);

            $transaction->save();

            $transaction->complete(function (Transaction $t) {
                $this->sendRequest();
            });

            return $transaction;
        });
    }
}
