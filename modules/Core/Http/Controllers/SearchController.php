<?php

namespace Modules\Core\Http\Controllers;

use Modules\Articles\Repositories\TagRepository;
use Modules\Core\Http\Controllers\System\FrontController;
use Modules\Articles\Repositories\ElasticSearchArticleRepository;

class SearchController extends FrontController
{

    /**
     * @param ElasticSearchArticleRepository $repository
     * @param TagRepository                  $tagRepository
     *
     * @return \View
     */
    public function search(ElasticSearchArticleRepository $repository, TagRepository $tagRepository)
    {
        $query = $this->request->get('query');
        $tag   = $this->request->get('tag');

        $articles  = null;
        $tagsCloud = null;

        if ( ! empty( $query )) {
            $articles = $repository->searchByKeyword($query);
            $articles->addQuery('query', $query);
        } elseif ( ! empty( $tag )) {
            $query    = $tag;
            $articles = $repository->searchByTag($query);
            $articles->addQuery('tag', $query);
        }

        if ( ! is_null($articles)) {
            $tagsCloud = $tagRepository->getTagsCloud(20, $articles->keyBy('id')->keys()->all());
        }

        return $this->setLayout('articles::article.search', compact('articles', 'tagsCloud', 'query'));
    }
}