<?php

namespace Modules\Articles\Http\Controllers;

use Bus;
use Modules\Articles\Jobs\PurchaseArticle;
use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\FrontController;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

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

        return $this->setLayout('article.byTag', compact('articles', 'tag'));
    }


    public function show(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        try {
            $isPurchased = Bus::dispatch(new PurchaseArticle($article, $this->user));
        } catch (NotEnoughMoneyException $e) {
            $isPurchased = false;
        }

        return $this->setLayout('article.show', [
            'article'     => $article,
            'isPurchased' => $isPurchased,
            'author'      => $article->author,
            'tags'        => $article->tagList,
        ]);
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
