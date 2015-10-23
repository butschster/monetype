<?php

namespace Modules\Articles\Http\Controllers;

use Bus;
use Assets;
use Modules\Articles\Jobs\BlockArticle;
use Modules\Articles\Jobs\DraftArticle;
use Modules\Articles\Jobs\PublishArticle;
use Modules\Articles\Jobs\ApproveArticle;
use Modules\Articles\Jobs\PurchaseArticle;
use Modules\Articles\Repositories\TagRepository;
use Modules\Articles\Exceptions\PlagiarismException;
use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\FrontController;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;
use Modules\Articles\Exceptions\CheckForPlagiarismException;

class ArticleController extends FrontController
{

    /**
     * @param ArticleRepository $articleRepository
     * @param TagRepository     $tagRepository
     *
     * @return \View
     */
    public function index(ArticleRepository $articleRepository, TagRepository $tagRepository)
    {
        $articles = $articleRepository->paginate();
        $tagsCloud = $tagRepository->getTagsCloud();

        return $this->setLayout('article.index', compact('articles', 'tagsCloud'));
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param TagRepository     $tagRepository
     * @param string            $tag
     *
     * @return \View
     */
    public function indexByTag(ArticleRepository $articleRepository, TagRepository $tagRepository, $tag)
    {
        $articles = $articleRepository->paginateByTag($tag);
        $tagsCloud = $tagRepository->getTagsCloud();

        return $this->setLayout('article.byTag', compact('articles', 'tagsCloud', 'tag'));
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

        if ($article->isDrafted()) {
            $this->checkPermissions('preview', $article);
        }

        Assets::package('validation');

        return $this->setLayout('article.show', [
            'article'     => $article,
            'isPurchased' => $article->checkPurchaseStatus($this->user),
            'author'      => $article->author,
            'tags'        => $article->tags,
        ]);
    }


    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buy(ArticleRepository $articleRepository, $articleId)
    {
        $article = $articleRepository->findOrFail($articleId);

        try {
            Bus::dispatch(new PurchaseArticle($article, $this->user));
        } catch (NotEnoughMoneyException $e) {
            return $this->errorRedirect(trans('articles::article.message.not_enough_money'));
        }

        return back();
    }


    /**
     * @param ArticleRepository $articleRepository
     *
     * @return \View
     */
    public function create(ArticleRepository $articleRepository)
    {
        Assets::package(['simplemde', 'rangeslider', 'tagsinput']);
        $article = $articleRepository->getModel();

        $this->checkPermissions('create', $article);

        $this->templateScripts['ARTICLE_ID'] = $article->id;

        return $this->setLayout('article.form', [
            'article' => $article,
            'action'  => 'front.article.store',
            'tags'    => []
        ]);
    }

    /**
     * @param ArticleRepository $articleRepository
     * @param integer           $articleId
     *
     * @return \View
     */
    public function edit(ArticleRepository $articleRepository, $articleId)
    {
        Assets::package(['simplemde', 'rangeslider', 'tagsinput']);
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('update', $article);

        $this->templateScripts['ARTICLE_ID'] = $article->id;

        $action = ['front.article.update', $articleId];
        if ( ! $this->user->can('update', $article)) {
            $action = ['front.article.correct', $articleId];
        }

        return $this->setLayout('article.form', [
            'article' => $article,
            'action'  => $action,
            'tags'    => array_combine($article->tagsArray, $article->tagsArray)
        ]);
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