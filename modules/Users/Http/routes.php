<?php

Route::group(['as' => 'front.'], function () {
    Route::get('profile/articles', ['as' => 'user.articles', 'uses' => 'UserController@articles']);
    Route::get('profile/{id?}', ['as' => 'profile.showById', 'uses' => 'ProfileController@showById']);
    Route::get('@{username}', ['as' => 'profile.showByUsername', 'uses' => 'ProfileController@showByUsername']);

    Route::group(['middleware' => 'auth', 'prefix' => 'me'], function () {
        Route::get('settings', ['as' => 'profile.settings', 'uses' => 'ProfileController@settings']);

        Route::get('coupon', ['as' => 'coupon.index', 'uses' => 'CouponController@index']);
        Route::post('coupon/activate', ['as' => 'coupon.activate', 'uses' => 'CouponController@activate']);
        Route::post('coupon', ['as' => 'coupon.create', 'uses' => 'CouponController@create']);
        Route::delete('coupon/{id}', ['as' => 'coupon.delete', 'uses' => 'CouponController@delete']);
    });
});

Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);