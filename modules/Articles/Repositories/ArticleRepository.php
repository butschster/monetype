<?php

namespace Modules\Articles\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Modules\Users\Model\User;
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
     * @param array $data
     *
     * @return Article
     */
    public function create(array $data)
    {
        $tags = array_pull($data, 'tags_list', '');

        $article = parent::create($data);

        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }

        if (count($tags) > 0) {
            $article->attachTags($tags);
        }

        return $article;
    }


    /**
     * @param array   $data
     * @param integer $id
     * @param string  $attribute
     *
     * @return Article
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $tags = array_pull($data, 'tags_list', '');

        $article = parent::update($data, $id, $attribute);

        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }

        $article->updateTags($tags);

        return $article;
    }


    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $columns = ['*'])
    {
        return $this->getModel()
            ->with('author', 'tags')
            ->orderByDate()
            ->published()
            ->withFavorites()
            ->paginate($perPage, $columns);
    }


    /**
     * @param array|string $tag
     * @param int          $perPage
     * @param array        $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByTag($tag, $perPage = null, $columns = ['*'])
    {
        $tag = explode(',', $tag);

        return $this->getModel()
            ->with('author', 'tags')
            ->filterByTag($tag)
            ->orderByDate()
            ->published()
            ->withFavorites()
            ->paginate($perPage, $columns);
    }


    /**
     * @param array $ids
     * @param int   $perPage
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByTagIds(array $ids, $perPage = null, $columns = ['*'])
    {
        return $this->getModel()
            ->with('author', 'tags')
            ->filterByTagIds($ids)
            ->orderByDate()
            ->published()
            ->withFavorites()
            ->paginate($perPage, $columns);
    }
}