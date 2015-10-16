<?php

namespace Modules\Articles\Providers;

use Modules\Users\Model\User;
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

        $gate->define('check.index', function (User $user) {
            return $user->isAdmin();
        });

        $gate->define('check.view', function (User $user, Article $article) {
            return $user->isAdmin() or $article->authoredBy($user);
        });

        $gate->define('check.article.detail', function (User $user, Article $article) {
            return $user->isAdmin() or $article->authoredBy($user);
        });
    }


    public function register()
    {

    }
}