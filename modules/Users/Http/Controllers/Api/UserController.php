<?php

namespace Modules\Users\Http\Controllers\Api;

use Modules\Core\Http\Controllers\System\ApiController;
use Modules\Articles\Repositories\ElasticSearchArticleRepository;

class UserController extends ApiController
{

    /**
     * @param ElasticSearchArticleRepository $repository
     *
     * @return mixed
     */
    public function filterBookmarks(ElasticSearchArticleRepository $repository)
    {
        $ids      = $this->user->favorites()->lists('id')->all();
        $query    = $this->getParameter('query');
        $articles = null;
        if ( ! empty( $ids ) and ! empty( $query )) {
            $articles = $repository->searchByKeyword($query, null, [
                'filtered' => [
                    'filter' => [
                        'ids' => [
                            'values' => $ids,
                        ],
                    ],
                ],
            ]);
        } else {
            $articles = $this->user->favorites()->paginate();
        }

        $this->setContent(view('articles::article.partials.list', compact('articles')));
    }
}
