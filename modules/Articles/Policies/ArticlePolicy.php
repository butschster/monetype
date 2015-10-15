<?php

namespace Modules\Articles\Policies;

use Modules\Users\Model\User;
use Modules\Articles\Model\Article;

class ArticlePolicy
{

    /**
     * @param User $user
     *
     * @return bool
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isBlocked()) {
            return false;
        }
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function view(User $user, Article $article)
    {
        return (
            ! $article->isBlocked()
        and
            ! $article->isDrafted()
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function create(User $user, Article $article)
    {
        return true;
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function update(User $user, Article $article)
    {
        return (
            $article->isDrafted()
        and
            $user->id === $article->author_id
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function draft(User $user, Article $article)
    {
        return (
            !$article->isDrafted()
            and
            $user->id === $article->author_id
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function publish(User $user, Article $article)
    {
        return (
            $article->isDrafted()
            and
            $user->id === $article->author_id
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function approve(User $user, Article $article)
    {
        return (
            $article->isPublished()
            and
            $user->isModerator()
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function block(User $user, Article $article)
    {
        return $user->isModerator();
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function delete(User $user, Article $article)
    {
        return (
            $article->isDrafted()
            and
            $user->id === $article->author_id
        ) or $user->isModerator();
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function preview(User $user, Article $article)
    {
        return (
            $article->isDrafted()
            and
            $user->id === $article->author_id
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function moderate(User $user, Article $article)
    {
        return (
            $article->isDrafted()
        and
            $user->isModerator()
        );
    }


    /**
     * @param User    $user
     * @param Article $article
     *
     * @return bool
     */
    public function viewPurchasers(User $user, Article $article)
    {
        return (
            $user->id === $article->author_id
        or
            $user->isModerator()
        );
    }
}