<?php

namespace Modules\Core\Console\Commands;

use App;
use ModulesLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateJavaScriptLang extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lang:convert-to-js';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converting lang files to json';


    /**
     * Execute the console command.
     *
     * @param Filesystem $files
     *
     * @return mixed
     */
    public function fire(Filesystem $files)
    {
        $data = [];

        foreach (ModulesLoader::getRegisteredModules() as $module) {
            if ( ! is_dir($module->getLocalePath())) {
                continue;
            }

            $namespace = $module->getKey() == 'app' ? null : $module->getKey();

            $data = array_merge_recursive($data, $this->loadLangFromPath($files, $module->getLocalePath(), $namespace));

            $vendorPath = base_path(implode(DIRECTORY_SEPARATOR, ['resources', 'lang', 'vendor', $namespace]));

            if (is_dir($vendorPath)) {
                $data = array_merge_recursive($data, $this->loadLangFromPath($files, $vendorPath, $namespace));
            }
        }

        $langDirectory = public_path('js' . DIRECTORY_SEPARATOR . 'lang');

        if ( ! $files->exists($langDirectory)) {
            $files->makeDirectory($langDirectory, 0755, true);
        }

        foreach ($data as $locale => $translates) {
            $data = json_encode($translates);
            $file = $langDirectory . DIRECTORY_SEPARATOR . $locale . '.json';
            $files->put($file, $data);

            $this->line("<info>File [{$file}]</info> for locale: [{$locale}] created");
        }
    }


    /**
     * @param Filesystem $files
     * @param            $path
     * @param            $namespace
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function loadLangFromPath(Filesystem $files, $path, $namespace)
    {
        $data = [];

        foreach ($files->directories($path) as $localeDir) {
            $locale = basename($localeDir);
            foreach ($files->allFiles($localeDir) as $localeFile) {
                if (is_null($namespace)) {
                    $data[$locale][basename($localeFile, '.php')] = $files->getRequire($localeFile->getRealPath());
                } else {
                    $data[$locale][$namespace][basename($localeFile, '.php')] = $files->getRequire($localeFile->getRealPath());
                }
            }
        }

        return $data;
    }

}
