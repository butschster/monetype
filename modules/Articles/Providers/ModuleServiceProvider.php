<?php

namespace Modules\Articles\Providers;

use Validator;
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

        $gate->define('check.byUser', function (User $user, User $targetUser) {
            return $user->isAdmin();
        });

        $gate->define('check.article.detail', function (User $user, Article $article) {
            return $user->isAdmin() or $article->authoredBy($user);
        });

        Validator::extend('mintags', function ($attribute, $tags, $parameters, $validator) {
            if (is_string($tags)) {
                $tags = explode(',', $tags);
            }

            $tags = array_unique(array_map('trim', $tags));

            return count($tags) > array_get($parameters, 0);
        });

        Validator::replacer('mintags', function($message, $attribute, $rule, $parameters) {
            return str_replace(':size', $parameters[0], $message);
        });
    }


    public function register()
    {

    }
}