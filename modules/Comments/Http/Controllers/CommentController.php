<?php

namespace Modules\Comments\Http\Controllers;

use Modules\Articles\Repositories\ArticleRepository;
use Modules\Comments\Http\Requests\CommentPostRequest;
use Modules\Comments\Model\Comment;
use Modules\Core\Http\Controllers\System\FrontController;

class CommentController extends FrontController
{

    /**
     * @param CommentPostRequest $request
     * @param ArticleRepository  $repository
     * @param integer            $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(CommentPostRequest $request, ArticleRepository $repository, $articleId)
    {
        $article = $repository->findOrFail($articleId);

        if ($article->forbid_comment) {
            abort(403);
        }

        $comment = $article->comments()->create($request->only('title', 'text'));

        if ($request->has('parent_id')) {
            $rootComment = Comment::findOrFail($request->get('parent_id'));
            $comment->makeChildOf($rootComment);
        }

        return $this->successRedirect(
            trans('comments::comment.message.posted'),
            route('front.article.show', $article->id) . '#comment_' . $comment->id
        );
    }
}