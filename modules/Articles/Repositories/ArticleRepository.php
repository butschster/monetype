<?php

namespace Modules\Articles\Repositories;

use Modules\Articles\Model\Article;
use Modules\Support\Helpers\Repository;

class ArticleRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return Article::class;
    }


    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->getModel()
            ->with('author')
            ->orderByDate()
            ->paginate($perPage, $columns);
    }


    /**
     * @param array|string $tag
     * @param int          $perPage
     * @param array        $columns
     *
     * @return mixed
     */
    public function paginateByTag($tag, $perPage = 15, $columns = ['*'])
    {
        $tag = explode(',', $tag);

        return $this->getModel()
            ->with('author')
            ->filterByTag($tag)
            ->orderByDate()
            ->paginate($perPage, $columns);
    }
}