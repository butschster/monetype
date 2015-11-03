<?php

namespace Modules\Core\Http\Controllers\System;

use Meta;

abstract class FrontController extends TemplateController
{

    /**
     * @var  \View  page template
     */
    public $layout = 'core::layout.main';


    public function registerMedia()
    {
        parent::registerMedia();
        Meta::loadPackage('libraries', 'app');
    }
}