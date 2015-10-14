<?php

namespace Modules\Articles\Providers;

use Modules\Articles\Model\Article;
use Modules\Articles\Policies\ArticlePolicy;
use Modules\Articles\Observers\ArticleObserver;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class ModuleServiceProvider extends AuthServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Article::class => ArticlePolicy::class
    ];


    /**
     * @param GateContract $gate
     */
    public function boot(GateContract $gate)
    {
        parent::registerPolicies($gate);

        Article::observe(new ArticleObserver);
    }


    public function register()
    {

    }
}