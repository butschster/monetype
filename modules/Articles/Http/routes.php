<?php

Route::get('articles/search/{tag}', ['as' => 'articles.byTag', 'uses' => 'ArticleController@byTag']);
Route::get('article/{article}/money', ['as' => 'article.money', 'uses' => 'ArticleController@money']);
Route::resource('article', 'ArticleController');

Route::get('category/{slug}', ['as' => 'category.show', 'uses' => 'CategoryController@show']);
Route::get('categories', ['as' => 'category.index', 'uses' => 'CategoryController@index']);