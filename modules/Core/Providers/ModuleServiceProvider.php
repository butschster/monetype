<?php

namespace Modules\Core\Providers;

use Lang;
use Event;
use Carbon\Carbon;
use Modules\Support\Assets\Meta;
use Modules\Support\Helpers\Profiler;
use Modules\Core\Console\Commands\DropDatabaseCommand;
use Modules\Core\Console\Commands\ElasticSearchIndexer;
use Modules\Core\Console\Commands\GenerateJavaScriptLang;

class ModuleServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand(DropDatabaseCommand::class);
        $this->registerConsoleCommand(GenerateJavaScriptLang::class);
        $this->registerConsoleCommand(ElasticSearchIndexer::class);

        Event::listen('illuminate.query', function($sql, $bindings, $time) {
            $sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
            $sql = vsprintf($sql, $bindings);

            Profiler::append('Database', $sql, $time / 1000);
        });

        $this->app->singleton('meta', function ($app)
        {
            return new Meta;
        });
    }

    public function boot()
    {
        Carbon::setLocale(Lang::locale());
    }
}