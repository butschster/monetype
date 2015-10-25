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
    public function saved(Article $article)
    {
        $article->addToIndex();
    }

    /**
     * @param Article $article
     */
    public function deleted(Article $article)
    {
        $article->deleteIndex();
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
        list( $text, $textIntro, $readMoreText ) = MarkdownParser::parseText($article->text_source);

        $article->text_intro     = $textIntro;
        $article->read_more_text = $readMoreText;
        $article->text           = $text;

        $words = str_word_count(strip_tags($text));
        $min   = floor($words / (int) config('article.words_per_minute_reading', 200));

        $article->reading_time = $min;
    }
}