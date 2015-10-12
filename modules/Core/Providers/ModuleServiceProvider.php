<?php

namespace Modules\Core\Providers;

use Lang;
use Carbon\Carbon;
use Modules\Core\Console\Commands\DropDatabaseCommand;
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
    }

    public function boot()
    {
        Carbon::setLocale(Lang::locale());
    }
}