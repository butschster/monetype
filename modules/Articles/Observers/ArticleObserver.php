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

        $cut = '---read-more---';
        if (strpos($article->text_source, $cut) !== false) {
            list( $textIntro, $text ) = explode($cut, $article->text_source, 2);
        } else {
            $textIntro = '';
            $text      = $article->text_source;
        }

        $article->text_intro = $parser->text($textIntro);
        $article->text       = $parser->text($text);
    }
}