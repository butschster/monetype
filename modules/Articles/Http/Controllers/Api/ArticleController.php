<?php

namespace Modules\Articles\Http\Controllers\Api;

use KodiCMS\API\Http\Controllers\Controller;
use Modules\Articles\Repositories\ArticleRepository;

class ArticleController extends Controller
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
}
