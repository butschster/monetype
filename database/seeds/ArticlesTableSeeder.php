<?php

use Illuminate\Database\Seeder;
use Modules\Articles\Model\Tag;
use Modules\Articles\Model\Article;

class ArticlesTableSeeder extends Seeder
{

    public function run()
    {
        Article::truncate();

        factory(Article::class, 'article', 50)->create()->each(function (Article $article) {
            $tags = Tag::take(rand(1, 6))->orderByRaw('RAND()')->get()->lists('name', 'id')->all();
            $article->attachTags($tags);

            $article->setPublished();
        });
    }
}
