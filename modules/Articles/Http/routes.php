<?php
Route::group(['as' => 'front.'], function () {

    //Route::get('/', ['as' => 'main', 'uses' => 'ArticleController@index']);

    Route::get('articles', ['as' => 'articles.index', 'uses' => 'ArticleController@index']);
    Route::get('articles/tag/{tag}', ['as' => 'articles.byTag', 'uses' => 'ArticleController@indexByTag']);

    Route::get('article/create', ['middleware' => 'auth', 'as' => 'article.create', 'uses' => 'ArticleController@create']);

    Route::get('article/{article}#comments', ['as' => 'article.comments', 'uses' => 'ArticleController@show']);
    Route::get('article/{article}#cut', ['as' => 'article.showCut', 'uses' => 'ArticleController@show']);
    Route::get('article/{article}', ['as' => 'article.show', 'uses' => 'ArticleController@show']);

    Route::get('category/{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
    Route::get('categories', ['as' => 'category.index', 'uses' => 'CategoryController@index']);

    Route::group(['middleware' => 'auth'], function () {

        Route::get('article/{article}/money', ['as' => 'article.money', 'uses' => 'ArticleController@money']);
        Route::put('article/{article}/approve', ['as' => 'article.approve', 'uses' => 'ArticleController@approve']);
        Route::post('article/{article}/buy', ['as' => 'article.buy', 'uses' => 'ArticleController@buy']);

        Route::get('profile/{userId}/checks', ['as' => 'checks.byUser', 'uses' => 'ArticleCheckController@listByUser']);
        Route::get('articles/checks', ['as' => 'checks.index', 'uses' => 'ArticleCheckController@index']);
        Route::get('article/{article}/checks/{id}', ['as' => 'article.checks.details', 'uses' => 'ArticleCheckController@details']);
        Route::get('article/{article}/checks', ['as' => 'article.checks', 'uses' => 'ArticleCheckController@listByArticle']);

        RouteAPI::post('article.favorite', ['uses' => 'Api\ArticleController@favorite']);
        RouteAPI::post('article.store', ['as' => 'article.store', 'uses' => 'Api\ArticleController@store']);
        RouteAPI::put('article.update/{article}', ['as' => 'article.update', 'uses' => 'Api\ArticleController@update']);
        RouteAPI::put('article.correct/{article}', ['as' => 'article.correct', 'uses' => 'Api\ArticleController@correct']);
        RouteAPI::post('article.publish/{article}', ['as' => 'article.publish', 'uses' => 'Api\ArticleController@publish']);
        RouteAPI::post('article.draft/{article}', ['as' => 'article.draft', 'uses' => 'Api\ArticleController@draft']);
        RouteAPI::post('article.approve/{article}', ['as' => 'article.approve', 'uses' => 'Api\ArticleController@approve']);
        RouteAPI::post('article.block/{article}', ['as' => 'article.block', 'uses' => 'Api\ArticleController@block']);

        RouteAPI::get('tags.search', ['uses' => 'Api\TagsController@search']);

        RouteAPI::get('article.search.bookmarked', ['uses' => 'Api\ArticleSearchController@searchBookmarked']);
        RouteAPI::get('article.search', ['uses' => 'Api\ArticleSearchController@searchAll']);

        Route::resource('article', 'ArticleController', [
            'except' => [
                'index', 'destroy', 'store', 'update', 'show', 'create'
            ],
        ]);

    });

});