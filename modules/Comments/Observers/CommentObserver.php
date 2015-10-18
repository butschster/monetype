<?php

namespace Modules\Comments\Observers;

use Request;
use Modules\Comments\Model\Comment;

class CommentObserver
{
    /**
     * @param Comment $comment
     */
    public function creating(Comment $comment)
    {
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