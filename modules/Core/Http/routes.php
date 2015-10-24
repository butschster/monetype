<?php

Route::group(['as' => 'front.'], function () {
    Route::get('/', ['as' => 'main', 'uses' => 'SoonController@index']);
    Route::get('search', ['as' => 'search', 'uses' => 'SearchController@search']);
    Route::get('articles/tag/{tag}', ['as' => 'articles.byTag', 'uses' => 'SearchController@searchByTag']);
});