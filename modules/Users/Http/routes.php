<?php

Route::group(['as' => 'front.'], function () {
    Route::get('profile/{id?}', ['as' => 'profile.showById', 'uses' => 'ProfileController@showById']);
    Route::get('@{username}', ['as' => 'profile.showByUsername', 'uses' => 'ProfileController@showByUsername']);

    Route::group(['middleware' => 'auth', 'prefix' => 'me'], function () {
        Route::get('settings', ['as' => 'profile.settings', 'uses' => 'ProfileController@settings']);

        Route::get('coupon', ['as' => 'user.activate_coupon', 'uses' => 'AccountController@coupon']);
        Route::post('coupon', ['as' => 'user.activate_coupon.post', 'uses' => 'AccountController@activateCoupon']);
        Route::get('account/add', ['as' => 'user.account.add', 'uses' => 'AccountController@add']);
    });
});

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);