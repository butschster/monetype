<?php

Route::get('/profile/{id?}', ['as' => 'profile.show', 'uses' => 'User\ProfileController@show']);

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);