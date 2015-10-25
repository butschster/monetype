<?php

namespace Modules\Articles\Repositories;

use Illuminate\Support\Collection;
use Modules\Articles\Model\Article;
use Modules\Support\Contracts\SearchRepositoryContract;

class ElasticSearchArticleRepository implements SearchRepositoryContract
{

    /**
     * @param string $query
     *
     * @return Collection
     */
    public function search($query = "")
    {
        return Article::search($query, $this->perPage(), $this->getOffset());
    }


    /**
     * @param string    $keyword
     * @param User|null $user
     *
     * @return Collection
     */
    public function searchByKeyword($keyword, User $user = null)
    {
        if (is_null($user)) {
            $query = [
                'match_all' => [],
            ];
        } else {
            $query = [
                'filtered' => [
                    'query'  => [
                        'match_all' => [],
                    ],
                    'filter' => [
                        'author_id' => $user->id,
                    ],
                ],
            ];
        }

        return Article::searchByQuery($query, $this->perPage(), $this->getOffset());
    }


    /**
     * @param string    $tag
     * @param User|null $user
     *
     * @return Collection
     */
    public function searchByTag($tag, User $user = null)
    {
        $tags = array_unique(array_map('trim', explode(',', $tag)));

        if (is_null($user)) {
            $query = [
                'terms' => [
                    'tags' => $tags,
                ],
            ];
        } else {
            $query = [
                'filtered' => [
                    'query'  => [
                        'terms' => [
                            'tags' => $tags,
                        ],
                    ],
                    'filter' => [
                        'author_id' => $user->id,
                    ],
                ],
            ];
        }

        return Article::searchByQuery($query, $this->perPage(), $this->getOffset());
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return (int) \Request::query('page', 1);
    }


    /**
     * @return int
     */
    public function perPage()
    {
        return 15;
    }

    public function getOffset()
    {
        return $this->perPage() * ($this->getCurrentPage() - 1);
    }
}