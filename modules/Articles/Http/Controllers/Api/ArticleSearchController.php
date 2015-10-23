<?php

namespace Modules\Articles\Http\Controllers\Api;

use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\ApiController;

class ArticleSearchController extends ApiController
{

    /**
     * @param ArticleRepository $articleRepository
     *
     * @return \Illuminate\View\View
     */
    public function searchAll(ArticleRepository $articleRepository)
    {
        $keyword = $this->getRequiredParameter('keyword');
        $articles = $articleRepository->searchByKeyword($keyword);

        return view('articles::article.partials.list', compact('articles'));
    }


    /**
     * @param ArticleRepository $articleRepository
     *
     * @return \Illuminate\View\View
     */
    public function searchBookmarked(ArticleRepository $articleRepository)
    {
        $tag = $this->getRequiredParameter('tag');
        $articles = $articleRepository->searchByTag($tag, auth()->user());

        return view('articles::article.partials.list', compact('articles'));
    }
}