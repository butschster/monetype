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
        return Article::search($query);
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
                'match' => [
                    'text_source' => $keyword,
                ],
            ];
        } else {
            $query = [
                'filtered' => [
                    'query'  => [
                        'match' => [
                            'text_source' => $keyword,
                        ],
                    ],
                    'filter' => [
                        'author_id' => $user->id,
                    ],
                ],
            ];
        }

        return Article::searchByQuery($query);
    }


    /**
     * @param string    $tag
     * @param User|null $user
     *
     * @return Collection
     */
    public function searchByTag($tag, User $user = null)
    {
        if (is_null($user)) {
            $query = [
                'match' => [
                    'tags' => $tag,
                ],
            ];
        } else {
            $query = [
                'filtered' => [
                    'query'  => [
                        'match' => [
                            'tags' => $tag,
                        ],
                    ],
                    'filter' => [
                        'author_id' => $user->id,
                    ],
                ],
            ];
        }

        return Article::searchByQuery($query);
    }
}