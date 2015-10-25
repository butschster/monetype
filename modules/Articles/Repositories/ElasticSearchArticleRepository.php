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
    public function searchByKeyword($keyword, User $user = null, array $customQuery = null)
    {
        $query = [
            'filtered' => [
                'query'  => [
                    'multi_match' => [
                        'query' => $keyword,
                        'type' => 'phrase',
                        'fields' => ['title', 'text_source'],
                    ],
                ]
            ],
        ];

        if ( ! is_null($user)) {
            array_set($query, 'filtered.filter', [
                'author_id' => $user->id,
            ]);
        }

        if (is_array($customQuery)) {
            $query = array_merge_recursive($query, $customQuery);
        }

        return Article::searchByQuery($query, $this->perPage(), $this->getOffset());
    }


    /**
     * @param string    $tag
     * @param User|null $user
     *
     * @return Collection
     */
    public function searchByTag($tag, User $user = null, array $customQuery = null)
    {
        $tags = array_unique(array_map('trim', explode(',', $tag)));

        $query = [
            'filtered' => [
                'query'  => [
                    'terms' => [
                        'tags' => $tags
                    ],
                ]
            ],
        ];

        if ( ! is_null($user)) {
            array_set($query, 'filtered.filter', [
                'author_id' => $user->id,
            ]);
        }

        if (is_array($customQuery)) {
            $query = array_merge_recursive($query, $customQuery);
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