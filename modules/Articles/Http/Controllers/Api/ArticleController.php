<?php

namespace Modules\Articles\Http\Controllers\Api;

use Bus;
use Modules\Articles\Jobs\ApproveArticle;
use Modules\Articles\Jobs\BlockArticle;
use Modules\Articles\Jobs\DraftArticle;
use Modules\Articles\Jobs\PublishArticle;
use Modules\Articles\Repositories\ArticleRepository;
use Modules\Articles\Exceptions\PlagiarismException;
use Modules\Core\Http\Controllers\System\ApiController;
use Modules\Articles\Http\Requests\StoreArticleRequest;
use Modules\Articles\Http\Requests\UpdateArticleRequest;
use Modules\Articles\Http\Requests\CorrectArticleRequest;
use Modules\Articles\Exceptions\CheckForPlagiarismException;

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

        $articleRepository->create($request->only('title', 'text_source', 'disable_comments', 'disable_stat_views',
            'disable_stat_pays', 'tags_list', 'cost'));

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

        $article = $articleRepository->update($request->only('title', 'text_source', 'disable_comments',
            'disable_stat_views', 'disable_stat_pays', 'tags_list', 'cost'), $articleId);

        $this->setMessage(trans('articles::article.message.updated'));
    }


    /**
     * @param CorrectArticleRequest $request
     * @param ArticleRepository    $articleRepository
     * @param integer              $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function correct(CorrectArticleRequest $request, ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('correct', $article);

        $article = $articleRepository->update($request->only('title', 'disable_comments',
            'disable_stat_views', 'disable_stat_pays', 'tags_list', 'cost'), $articleId);

        $this->setMessage(trans('articles::article.message.updated'));
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publish(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('publish', $article);

        try {
            Bus::dispatch(new PublishArticle(auth()->user(), $article));
        } catch (PlagiarismException $e) {
            return $this->errorRedirect(trans('articles::article.message.plagiarism'));
        } catch (CheckForPlagiarismException $e) {
            return $this->errorRedirect(trans('articles::article.message.cant_check_for_plagiarism',
                ['error' => $e->getMessage()]));
        }

        return redirect(route('front.article.edit', $article->id));
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function draft(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('draft', $article);

        Bus::dispatch(new DraftArticle(auth()->user(), $article));


        return redirect(route('front.article.edit', $article->id));
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('approve', $article);

        Bus::dispatch(new ApproveArticle(auth()->user(), $article));

        $this->setMessage(trans('articles::article.message.approved'));
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('block', $article);

        $this->validate(\Request::all(), [
            'block_reason' => 'required'
        ], [], trans('articles::article.field'));

        Bus::dispatch(new BlockArticle(auth()->user(), $article, $this->getParameter('block_reason')));

        $this->setMessage(trans('articles::article.message.blocked'));
    }
}
