<?php

namespace Modules\Articles\Jobs;

use Modules\Users\Model\User;
use Modules\Articles\Model\Article;
use Illuminate\Contracts\Bus\SelfHandling;

class BlockArticle implements SelfHandling
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
     * @var string
     */
    protected $reason;

    /**
     * @var bool
     */
    protected $notify = true;


    /**
     * @param User|null $user
     * @param Article   $article
     * @param bool|true $notify
     */
    public function __construct(User $user, Article $article, $reason, $notify = true)
    {
        $this->user    = $user;
        $this->article = $article;
        $this->reason  = $reason;
        $this->notify  = (bool) $notify;
    }


    public function handle()
    {
        $this->article->setBlocked($this->user, $this->reason);
    }
}
