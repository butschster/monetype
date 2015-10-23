<?php

namespace Modules\Users\Http\Controllers;

use Modules\Core\Http\Controllers\System\FrontController;

class UserController extends FrontController
{

    /**
     * @return \View
     */
    public function articles()
    {
        $articles = $this->user->articles()->paginate();

        return $this->setLayout('user.articles', compact('articles'));
    }

    /**
     * @return \View
     */
    public function bookmarks()
    {
        $articles = $this->user->favorites()->paginate();

        return $this->setLayout('user.bookmarks', compact('articles'));
    }
}
