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
     * TODO: переделать поиск статей
     * @param string    $keyword
     * @param User|null $user
     * @param int       $limit
     *
     * @return Collection
     */
    public function searchByKeyword($keyword, User $user = null, $limit = 100)
    {
        $query = $this->getModel()
            ->with('author', 'tags')
            ->where('text_source', 'like', "$keyword%");

        if ( ! is_null($user)) {
            $query->where('author_id', $user->id);
        }

        return $query
            ->published()
            ->orWhereHas('tags', function ($query) use($keyword) {
                $query->where('name', 'like', "$keyword%")->orderBy('count', 'desc');
            })
            ->orderBy('count_payments', 'desc')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * @param string    $tag
     * @param User|null $user
     * @param int       $limit
     *
     * @return Collection
     */
    public function searchByTag($tag, User $user = null, $limit = 100)
    {
        $query = $this->getModel()
                      ->with('author', 'tags');

        if ( ! is_null($user)) {
            $query->where('author_id', $user->id);
        }

        return $query
            ->published()
            ->orWhereHas('tags', function ($query) use($tag) {
                $query->where('name', 'like', "$tag%")->orderBy('count', 'desc');
            })
            ->orderBy('count_payments', 'desc')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
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
    public function paginate($perPage = 15, $columns = ['*'])
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
    public function paginateByTag($tag, $perPage = 15, $columns = ['*'])
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
}