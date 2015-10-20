<?php

namespace Modules\Docs\Model;

use ModulesLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Repository as Cache;


/**
 * @property integer    $id
 * @property integer    $count
 * @property string     $name
 *
 * @property Collection $articles
 */
class Doc 
{
    /**
     * The filesystem implementation.
     *
     * @var Filesystem
     */
    protected $files;
    /**
     * The cache implementation.
     *
     * @var Cache
     */
    protected $cache;
    /**
     * Create a new documentation instance.
     *
     * @param  Filesystem  $files
     * @param  Cache  $cache
     * @return void
     */
    public function __construct(Filesystem $files, Cache $cache)
    {
        $this->files = $files;
        $this->cache = $cache;
    }

    /**
     * Get the given documentation page.
     *
     * @param  string  $page
     * @return string
     */
    public function get($page)
    {
        return $this->cache->remember('docs.'.$page, 5, function() use ($page) {
            $modulePath = ModulesLoader::getRegisteredModule('Docs')->getPath();
            $path = $modulePath . '/Pages/' . $page . '.md';

            if ($this->files->exists($path)) {
                return markdown($this->files->get($path));
            }

            return null;
        });

    }
}
