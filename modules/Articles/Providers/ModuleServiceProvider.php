<?php

namespace Modules\Articles\Providers;

use Modules\Articles\Model\Article;
use Modules\Articles\Observers\ArticleObserver;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseServiceProvider;

class ModuleServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        Article::observe(new ArticleObserver);
    }
}