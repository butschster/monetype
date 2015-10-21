<?php

namespace Modules\Articles\Http\Controllers\Api;

use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\ApiController;
use Modules\Articles\Http\Requests\StoreArticleRequest;
use Modules\Articles\Http\Requests\UpdateArticleRequest;

class ArticleController extends ApiController
{

    /**
     * @param ArticleRepository $articleRepository
     */
    public function favorite(ArticleRepository $articleRepository)
    {
        $articleId = $this->getRequiredParameter('id');
        $article   = $articleRepository->findOrFail($articleId);

        $article->is_favorited = $article->toggleFavorite(auth()->user()) > 0;

        $this->setContent(view('articles::article.partials.favorites', compact('article')));
    }


    /**
     * @param StoreArticleRequest $request
     * @param ArticleRepository   $articleRepository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreArticleRequest $request, ArticleRepository $articleRepository)
    {
        $this->checkPermissions('create', $articleRepository->getModel());

        $articleRepository->create($request->only(
            'title', 'text_source', 'disable_comments', 'disable_stat_views', 'disable_stat_pays', 'tags'
        ));


        $this->setMessage(trans('articles::article.message.created'));
    }


    /**
     * @param UpdateArticleRequest $request
     * @param ArticleRepository    $articleRepository
     * @param integer              $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateArticleRequest $request, ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('update', $article);

        $article = $articleRepository->update($request->only(
            'title', 'text_source', 'disable_comments', 'disable_stat_views', 'disable_stat_pays', 'tags'
        ), $articleId);

        $this->setMessage(trans('articles::article.message.updated'));
    }
}
