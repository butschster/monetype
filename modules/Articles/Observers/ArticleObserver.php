<?php

namespace Modules\Articles\Observers;

use Parsedown;
use Modules\Articles\Model\Article;

class ArticleObserver
{

    /**
     * @param Article $article
     */
    public function creating(Article $article)
    {
        if (is_null($article->author_id)) {
            $article->assignAuthor(auth()->user());
        }

        $article->status = Article::STATUS_DRAFT;
    }


    /**
     * @param Article $article
     */
    public function saving(Article $article)
    {
        $parser = new Parsedown;
        $article->text_intro = $parser->text($article->text_intro_source);
        $article->text = $parser->text($article->text_source);
    }
}