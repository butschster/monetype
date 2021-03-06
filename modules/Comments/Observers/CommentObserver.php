<?php

namespace Modules\Comments\Observers;

use Auth;
use Akismet;
use Request;
use Parsedown;
use Modules\Comments\Model\Comment;

class CommentObserver
{

    /**
     * @param Comment $comment
     */
    public function creating(Comment $comment)
    {
        if (auth()->check() and (bool) config('comment.moderate', true)) {
            if (Akismet::validateKey()) {
                Akismet::setCommentAuthorEmail(auth()->user()->email);
                Akismet::setCommentContent($comment->text);

                if (Akismet::isSpam()) {
                    $comment->status = Comment::STATUS_SPAM;
                }
            }
        }

        if (is_null($comment->author_id)) {
            $comment->assignAuthor(auth()->user());
        }

        $parser = new Parsedown;

        $comment->user_ip   = Request::ip();
        $comment->text      = $parser->text($comment->text);


    }


    /**
     * @param Comment $comment
     */
    public function deleting(Comment $comment)
    {
        $comment->article->decrement('count_comments');
    }
}