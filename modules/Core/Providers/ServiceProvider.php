<?php

namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Registers a new console (artisan) command
     *
     * @param $class The command class
     *
     * @return void
     */
    public function registerConsoleCommand($class)
    {
        $this->commands($class);
    }
}