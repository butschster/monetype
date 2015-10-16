<?php

namespace Modules\Articles\Jobs;

use Bus;
use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Articles\Exceptions\PlagiarismException;

class PublishArticle implements SelfHandling
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var bool
     */
    protected $notify = true;


    /**
     * @param User|null $user
     * @param Article   $article
     * @param bool|true $notify
     */
    public function __construct(User $user, Article $article, $notify = true)
    {
        $this->user    = $user;
        $this->article = $article;
        $this->notify  = (bool) $notify;
    }


    /**
     * @return bool
     * @throws PlagiarismException
     * @throws \Modules\Articles\Exceptions\ArticleException
     */
    public function handle()
    {
        if ( ! $this->article->isChecked()) {
            $checkResult = Bus::dispatch(new CheckForPlagiarism($this->user, $this->article));
        } else {
            $checkResult = $this->article->getLastCheckResult();
        }

        if ($checkResult->isPlagiarism()) {
            throw new PlagiarismException($checkResult);
        }

        $this->article->setPublished();

        return true;
    }
}
