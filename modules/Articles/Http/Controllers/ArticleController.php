<?php

namespace Modules\Articles\Http\Controllers;

use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\FrontController;

class ArticleController extends FrontController
{

    /**
     * @param ArticleRepository $articleRepository
     *
     * @return \View
     */
    public function index(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->paginate();

        return $this->setLayout('article.index', compact('articles'));
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param string            $tag
     *
     * @return \View
     */
    public function indexByTag(ArticleRepository $articleRepository, $tag)
    {
        $articles = $articleRepository->paginateByTag($tag);

        return $this->setLayout('article.index', compact('articles'));
    }


    public function show($articleId)
    {

    }


    public function create()
    {

    }


    public function store()
    {

    }


    public function edit($articleId)
    {

    }


    public function update($articleId)
    {

    }


    public function destroy($articleId)
    {

    }


    public function money($articleId)
    {

    }
}
