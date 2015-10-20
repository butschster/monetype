<?php

namespace Modules\Comments\Http\Controllers;

use Modules\Articles\Repositories\ArticleRepository;
use Modules\Comments\Http\Requests\CommentPostRequest;
use Modules\Comments\Model\Comment;
use Modules\Core\Http\Controllers\System\FrontController;

class CommentController extends FrontController
{

    public function post(CommentPostRequest $request, ArticleRepository $repository, $articleId)
    {
        $article = $repository->findOrFail($articleId);
        $comment = $article->comments()->create($request->only('title', 'text'));

        if ($request->has('parent_id')) {
            $rootComment = Comment::findOrFail($request->get('parent_id'));
            $comment->makeChildOf($rootComment);
        }

        return $this->successRedirect(trans('comments::comment.message.posted'));
    }
}