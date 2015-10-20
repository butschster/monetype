<?php

Route::group(['as' => 'front.'], function () {
    Route::get('/', ['as' => 'main', 'uses' => 'SoonController@index']);
});