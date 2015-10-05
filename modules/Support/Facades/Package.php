<?php

namespace Modules\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Support\Assets\PackageManager;

class Package extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return PackageManager::class;
    }
}