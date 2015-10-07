<?php

namespace Modules\Core\Providers;

use Modules\Core\Console\Commands\DropDatabaseCommand;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseServiceProvider;

class ModuleServiceProvider extends BaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommand('DropDatabaseCommand', DropDatabaseCommand::class);
    }
}