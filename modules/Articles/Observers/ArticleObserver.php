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

        $pattern = "/<cut>(.*?)<\\/cut>/si";
        preg_match($pattern, $article->text_source, $matches);

        if ( ! empty($matches)) {
            $readMoreText = strip_tags($matches[1]);
            list($textIntro, $text) = preg_split($pattern, $article->text_source, 2);

        } else {
            $readMoreText = $textIntro = '';
            $text         = $article->text_source;
        }

        if ( ! empty($textIntro)) {
            $article->text_intro = $parser->text($textIntro);
        }

        $article->read_more_text = $readMoreText;
        $article->text           = $parser->text($text);

        $words = str_word_count(strip_tags($article->text));
        $min   = floor($words / (int) config('article.words_per_minute_reading', 200));

        $article->reading_time = $min;
    }
}