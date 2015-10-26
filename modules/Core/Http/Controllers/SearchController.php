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

        $articles  = null;
        $tagsCloud = [];

        if ( ! empty( $query )) {
            $articles = $repository->searchByKeyword($query);
            $articles->addQuery('query', $query);
        }

        if ( ! is_null($articles) and $articles->count() > 0) {
            $tagsCloud = $tagRepository->getTagsCloud(20, $articles->keyBy('id')->keys()->all());
        }

        $this->setTitle(trans('articles::article.title.search'));

        return $this->setLayout('articles::article.search', compact('articles', 'tagsCloud', 'query'));
    }


    /**
     * @param ElasticSearchArticleRepository $repository
     * @param TagRepository                  $tagRepository
     * @param string                         $tag
     *
     * @return \View
     */
    public function searchByTag(ElasticSearchArticleRepository $repository, TagRepository $tagRepository, $tag)
    {
        $tagsCloud = null;
        $articles = $repository->searchByTag($tag);

        if ( ! is_null($articles) and $articles->count() > 0) {
            $tagsCloud = $tagRepository->getTagsCloud(20, $articles->keyBy('id')->keys()->all());
        }

        $this->setTitle(trans('articles::article.title.by_tag', ['tag' => $tag]));

        return $this->setLayout('articles::article.byTag', compact('articles', 'tagsCloud', 'tag'));
    }
}