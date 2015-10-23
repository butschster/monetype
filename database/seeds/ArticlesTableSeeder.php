<?php

use Illuminate\Database\Seeder;
use Modules\Articles\Model\Tag;
use Modules\Articles\Model\Article;
use Modules\Articles\Model\ArticleCheck;
use Modules\Articles\Jobs\PublishArticle;
use Modules\Articles\Model\ArticleRevision;

class ArticlesTableSeeder extends Seeder
{

    public function run()
    {
        Article::truncate();
        ArticleCheck::truncate();
        ArticleRevision::truncate();

        if ( ! App::environment('local')) {
            return;
        }

        Config::set('article.check.test', true);
        if (config('article.check.max_percent_plagiarism') < 80) {
            Config::set('article.check.max_percent_plagiarism', 80);
        }

        factory(Article::class, 'article', 50)->create()->each(function (Article $article) {
            $tags = Tag::take(rand(1, 6))->orderByRaw('RAND()')->get()->lists('name', 'id')->all();

            $article->attachTags($tags);
            $article->need_check = true;

            try {
                Bus::dispatch(new PublishArticle($article->author, $article));
            } catch (Exception $e) {
                $this->command->error($e->getMessage());
            }
        });
    }
}
