<?php

namespace Modules\Articles\Providers;

use Modules\Articles\Model\Article;
use Modules\Core\Providers\ServiceProvider;
use Modules\Articles\Observers\ArticleObserver;

class ModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Article::observe(new ArticleObserver);
    }

    public function register()
    {

    }
}