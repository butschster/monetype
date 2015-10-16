<?php

use Illuminate\Database\Seeder;
use Modules\Articles\Model\Article;
use Modules\Comments\Model\Comment;

class CommentsTableSeeder extends Seeder
{

    public function run()
    {
        Comment::truncate();

        foreach(Article::all() as $article) {
            factory(Comment::class, 'comment', rand(1, 30))->create()->each(function (Comment $comment) use($article) {
                $comment->article()->associate($article)->save();
            });
        }
    }
}
