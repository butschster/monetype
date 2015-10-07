<?php

namespace App;

use KodiCMS\ModulesLoader\ModuleContainer as BaseModuleContainer;

class DefaultModuleContainer extends BaseModuleContainer
{

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return $this
     */
    public function boot($app)
    {
        if ( ! $this->isBooted) {
            $this->loadViews();
            $this->loadTranslations();
            $this->loadAssets();
            $this->isBooted = true;
        }

        return $this;
    }


    /**
     * @return string
     */
    public function getAssetsPackagesPath()
    {
        return $this->getPath(['resources', 'packages.php']);
    }


    protected function loadAssets()
    {
        if (is_file($packagesFile = $this->getAssetsPackagesPath())) {
            require $packagesFile;
        }
    }
}