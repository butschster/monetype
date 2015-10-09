<?php

Route::get('articles', ['as' => 'articles.index', 'uses' => 'ArticleController@index']);
Route::get('articles/search/{tag}', ['as' => 'articles.byTag', 'uses' => 'ArticleController@indexByTag']);
Route::get('article/{article}/money', ['as' => 'article.money', 'uses' => 'ArticleController@money']);

Route::get('category/{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
Route::get('categories', ['as' => 'category.index', 'uses' => 'CategoryController@index']);

Route::group(['middleware' => 'auth'], function () {
    Route::resource('article', 'ArticleController', [
        'except' => [
            'index',
            'destroy'
        ]
    ]);
});