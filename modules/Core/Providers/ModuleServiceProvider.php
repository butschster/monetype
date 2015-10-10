<?php

namespace Modules\Core\Providers;

use Modules\Core\Console\Commands\DropDatabaseCommand;

class ModuleServiceProvider extends ServiceProvider
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