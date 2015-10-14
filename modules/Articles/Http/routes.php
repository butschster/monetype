<?php
Route::group(['as' => 'front.'], function () {

    Route::get('/', ['as' => 'main', 'uses' => 'ArticleController@index']);

    Route::get('articles', ['as' => 'articles.index', 'uses' => 'ArticleController@index']);
    Route::get('articles/tag/{tag}', ['as' => 'articles.byTag', 'uses' => 'ArticleController@indexByTag']);
    Route::get('article/{article}/money', ['as' => 'article.money', 'uses' => 'ArticleController@money']);
    Route::get('article/{article}/preview', ['as' => 'article.preview', 'uses' => 'ArticleController@preview']);

    Route::put('article/{article}/publish', ['as' => 'article.publish', 'uses' => 'ArticleController@publish']);
    Route::put('article/{article}/draft', ['as' => 'article.draft', 'uses' => 'ArticleController@draft']);
    Route::put('article/{article}/approve', ['as' => 'article.approve', 'uses' => 'ArticleController@approve']);
    Route::put('article/{article}/block', ['as' => 'article.block', 'uses' => 'ArticleController@block']);

    Route::get('category/{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
    Route::get('categories', ['as' => 'category.index', 'uses' => 'CategoryController@index']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('article', 'ArticleController', [
        'except' => [
            'index',
            'destroy',
        ],
    ]);
});