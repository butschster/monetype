<?php

namespace Modules\Core\Console\Commands;

use App;
use Illuminate\Console\Command;
use Modules\Articles\Model\Article;

class ElasticSearchIndexer extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'elasticsearch:index';


    public function fire()
    {
        foreach (Article::all() as $article) {
            $article->addToIndex();
        }
    }
}
