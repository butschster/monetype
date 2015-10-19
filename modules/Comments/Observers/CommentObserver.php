<?php

namespace Modules\Comments\Observers;

use Akismet;
use Request;
use Modules\Comments\Model\Comment;

class CommentObserver
{

    /**
     * @param Comment $comment
     */
    public function creating(Comment $comment)
    {
        if (auth()->check()) {
            if (Akismet::validateKey()) {
                Akismet::setCommentAuthorEmail(auth()->user()->email);
                Akismet::setCommentContent($comment->text);

                if (Akismet::isSpam()) {
                    $comment->status = Comment::STATUS_SPAM;
                }
            }
        }

        $comment->user_ip = Request::ip();
    }


    /**
     * @param Comment $comment
     */
    public function deleting(Comment $comment)
    {
        $comment->article->decrement('count_comments');
    }
}