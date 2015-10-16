<?php

namespace Modules\Articles\Http\Controllers;

use Modules\Articles\Model\ArticleCheck;
use Modules\Articles\Repositories\ArticleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Http\Controllers\System\FrontController;

class ArticleCheckController extends FrontController
{

    public function index()
    {
        $this->checkPermissions('check.index');

        return $this->setLayout('check.index', [
            'checks' => ArticleCheck::with('article')->paginate()
        ]);
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \View
     */
    public function listByArticle(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('check.article', $article);

        return $this->setLayout('check.byArticle', [
            'article' => $article,
            'checks'  => $article->checks()->paginate()
        ]);
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     * @param integer           $checkId
     *
     * @return \View
     */
    public function details(ArticleRepository $articleRepository, $articleId, $checkId)
    {
        $article = $articleRepository->findOrFail($articleId);
        $check   = $article->checks()->where('id', $checkId)->first();

        if (is_null($check)) {
            throw new ModelNotFoundException;
        }

        $this->checkPermissions('check.article.detail', $article);

        return $this->setLayout('check.details', [
            'article' => $article,
            'check'   => $check
        ]);
    }
}