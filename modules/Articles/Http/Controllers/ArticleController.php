<?php

namespace Modules\Articles\Http\Controllers;

use Bus;
use Meta;
use Modules\Articles\Jobs\PurchaseArticle;
use Modules\Articles\Repositories\TagRepository;
use Modules\Articles\Repositories\ArticleRepository;
use Modules\Core\Http\Controllers\System\FrontController;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

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

        $this->setTitle(trans('articles::article.title.list'));

        return $this->setLayout('article.index', compact('articles', 'tagsCloud'));
    }

    /**
     * @param ArticleRepository $articleRepository
     * @param TagRepository     $tagRepository
     * @param string            $period
     *
     * @return \View
     */
    public function top(ArticleRepository $articleRepository, TagRepository $tagRepository, $period = null)
    {
        $articles = $articleRepository->getTopList($period);
        $tagsCloud = $tagRepository->getTagsCloud();

        $this->setTitle(trans('articles::article.title.top'));

        return $this->setLayout('article.index', compact('articles', 'tagsCloud'));
    }

    /**
     * @param ArticleRepository $articleRepository
     *
     * @return \View
     */
    public function listThematic(ArticleRepository $articleRepository)
    {
        $tags = $this->user->tags;

        if ( $tags->count() > 0) {
            $articles = $articleRepository->paginateByTagIds($tags->lists('id')->all());
        } else {
            $articles = [];
        }

        Meta::addPackage(['tagsinput']);
        $this->setTitle(trans('articles::article.title.thematic'));

        return $this->setLayout('article.thematic', compact('articles', 'tags'));
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

        Meta::addPackage('validation')->addSocialTags($article);

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
     * @return \View
     */
    public function showByUsername(ArticleRepository $articleRepository, $username, $articleId)
    {
        $article = $articleRepository->getModel($articleId)->with('author')->whereHas('author', function($q) use($username) {
            $q->where('username', $username);
        })->where('id', $articleId)->first();

        if(is_null($article)) {
            abort(404);
        }

        if ($article->isDrafted()) {
            $this->checkPermissions('preview', $article);
        }

        Meta::addPackage('validation')->addSocialTags($article);

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
        $article = $articleRepository->getModel();

        $this->checkPermissions('create', $article);

        Meta::addPackage(['simplemde', 'rangeslider', 'tagsinput']);
        $this->templateScripts['ARTICLE_ID'] = $article->id;

        $this->setTitle(trans('articles::article.title.create'));

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
        $article = $articleRepository->findOrFail($articleId);

        $this->checkPermissions('update', $article);
		
        Meta::addPackage(['simplemde', 'rangeslider', 'tagsinput']);
        $this->templateScripts['ARTICLE_ID'] = $article->id;

        $action = ['front.article.update', $articleId];
        if ( ! $this->user->can('update', $article)) {
            $action = ['front.article.correct', $articleId];
        }

        $this->setTitle(trans('articles::article.title.edit'));

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

        $this->setTitle(trans('articles::article.title.money', ['article' => $article->title]));

        return $this->setLayout('article.money', [
            'article'      => $article,
            'transactions' => $article->recipients()->with('debitAccount')->paginate(),
        ]);
    }
}