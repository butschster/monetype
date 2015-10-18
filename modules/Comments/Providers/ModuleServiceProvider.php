<?php

namespace Modules\Comments\Providers;

use Modules\Comments\Model\Comment;
use Modules\Core\Providers\ServiceProvider;
use Modules\Comments\Observers\CommentObserver;

class ModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Comment::observe(new CommentObserver);
    }


    public function register()
    {

    }
}