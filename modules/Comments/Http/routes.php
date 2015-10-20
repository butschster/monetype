<?php
Route::group(['as' => 'front.', 'middleware' => 'auth'], function () {
    Route::post('article/{article}/comment', ['as' => 'comment.post', 'uses' => 'CommentController@post']);
});