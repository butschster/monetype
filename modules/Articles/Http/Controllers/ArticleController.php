<?php

namespace Modules\Articles\Http\Controllers;

use Bus;
use Modules\Articles\Jobs\ApproveArticle;
use Modules\Articles\Jobs\BlockArticle;
use Modules\Articles\Jobs\DraftArticle;
use Modules\Articles\Jobs\PublishArticle;
use Modules\Articles\Jobs\PurchaseArticle;
use Modules\Articles\Repositories\ArticleRepository;
use Modules\Articles\Http\Requests\StoreArticleRequest;
use Modules\Articles\Http\Requests\UpdateArticleRequest;
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


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \View
     */
    public function show(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('view', $article);

        try {
            $isPurchased = Bus::dispatch(new PurchaseArticle($article, $this->user));
        } catch (NotEnoughMoneyException $e) {
            $isPurchased = false;
        }

        return $this->setLayout('article.show', [
            'article'     => $article,
            'isPurchased' => $isPurchased,
            'author'      => $article->author,
            'tags'        => $article->tags,
        ]);
    }


    /**
     * @param ArticleRepository $articleRepository
     *
     * @return \View
     */
    public function create(ArticleRepository $articleRepository)
    {
        $article = $articleRepository->getModel();

        $this->checkPermissions('create', $article);

        return $this->setLayout('article.form', [
            'article' => $article,
            'action'  => 'front.article.store',
            'tags'    => ''
        ]);
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

        $article = $articleRepository->create($request->only(
            'title', 'text_source', 'forbid_comment', 'tags'
        ));

        return $this->successRedirect(
            trans('articles::article.message.created'),
            route('front.article.preview', $article->id)
        );
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \View
     */
    public function edit(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('update', $article);

        return $this->setLayout('article.form', [
            'article' => $article,
            'action'  => ['front.article.update', $articleId],
            'tags'    => $article->tagsString
        ]);
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
            'title', 'text_source', 'forbid_comment', 'tags'
        ), $articleId);

        return $this->successRedirect(
            trans('articles::article.message.updated'),
            route('front.article.preview', $article->id)
        );
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \View
     */
    public function preview(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('preview', $article);

        return $this->setLayout('article.preview', [
            'article' => $article,
            'tags'        => $article->tags
        ]);
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

        Bus::dispatch(new PublishArticle($this->user, $article));

        return $this->successRedirect(
            trans('articles::article.message.published')
        );
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

        Bus::dispatch(new DraftArticle($this->user, $article));

        return $this->successRedirect(
            trans('articles::article.message.drafted')
        );
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

        Bus::dispatch(new ApproveArticle($this->user, $article));

        return $this->successRedirect(
            trans('articles::article.message.approved')
        );
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

        $this->validate($this->request, [
            'block_reason' => 'required'
        ], [], trans('articles::article.field'));

        Bus::dispatch(new BlockArticle($this->user, $article, $this->request->get('block_reason')));

        return $this->successRedirect(
            trans('articles::article.message.approved')
        );
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('delete', $article);

        $articleRepository->delete($articleId);

        return $this->successRedirect(
            trans('articles::article.message.deleted')
        );
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \View
     */
    public function money(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('viewPurchasers', $article);

        return $this->setLayout('article.money', [
            'article'      => $article,
            'transactions' => $article->recipients()->with('debitAccount')->paginate(),
        ]);
    }
}