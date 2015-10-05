<?php

namespace Modules\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Support\Assets\Assets as AssetsClass;

class Assets extends Facade
{

    /**
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return AssetsClass::class;
    }
}