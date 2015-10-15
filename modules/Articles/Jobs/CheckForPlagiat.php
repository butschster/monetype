<?php

namespace Modules\Articles\Jobs;

use Modules\Articles\Exceptions\CheckForPlagiatException;
use Modules\Articles\Model\Article;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Articles\Model\ArticleCheck;

class CheckForPlagiat implements SelfHandling
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
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }


    public function handle()
    {
        $response = $this->sendRequest();

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
     * TODO: при отправке статьи на проверку списывать с автора стоимость проверки
     *
     * @return array
     * @throws CheckForPlagiatException
     */
    protected function sendRequest()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'text' => $this->article->text,
            'key'  => $this->key,
            'test' => 1,
        ]);

        curl_setopt($curl, CURLOPT_URL, 'http://www.content-watch.ru/public/api/');
        $return = json_decode(trim(curl_exec($curl)), true);
        curl_close($curl);

        if ( ! empty( $return['error'] )) {
            throw new CheckForPlagiatException($return['error']);
        }

        return $return;
    }
}
