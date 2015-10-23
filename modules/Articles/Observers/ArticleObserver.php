<?php

namespace Modules\Articles\Observers;

use Request;
use Modules\Articles\Model\Article;
use Modules\Support\Helpers\MarkdownParser;

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

        $article->user_ip = Request::ip();
        $article->status  = Article::STATUS_DRAFT;

        $this->parseText($article);
    }


    /**
     * @param Article $article
     */
    public function saving(Article $article)
    {

    }


    /**
     * @param Article $article
     */
    public function updating(Article $article)
    {
        if ($article->isDirty('text_source')) {
            $article->need_check = true;
            $this->parseText($article);
        }
    }


    /**
     * @param Article $article
     */
    protected function parseText(Article $article)
    {
        $parser = new MarkdownParser;

        // TODO: доработать
        $cut = '<cut></cut>';
        if (strpos($article->text_source, $cut) !== false) {
            list( $textIntro, $text ) = explode($cut, $article->text_source, 2);
        } else {
            $textIntro = '';
            $text      = $article->text_source;
        }

        if ( ! empty( $textIntro )) {
            $article->text_intro = $parser->text($textIntro);
        }

        $article->text = $parser->text($text);
    }
}