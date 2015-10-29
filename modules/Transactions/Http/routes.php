<?php

Route::group(['middleware' => 'auth'], function () {

    // PayPal
    Route::get('payments/paypal/create', ['as' => 'payments.paypal.create', 'uses' => 'PayPalController@create']);
    Route::get('payments/paypal/success', ['as' => 'payments.paypal.success', 'uses' => 'PayPalController@success']);
    Route::get('payments/paypal/cancel', ['as' => 'payments.paypal.cancel', 'uses' => 'PayPalController@cancel']);

    // Robokassa
    Route::get('payments/robokassa/create', ['as' => 'payments.robokassa.create', 'uses' => 'RobokassaController@create']);
    Route::post('payments/robokassa/result', ['as' => 'payments.robokassa.result', 'uses' => 'RobokassaController@result']);
    Route::post('payments/robokassa/success', ['as' => 'payments.robokassa.success', 'uses' => 'RobokassaController@success']);
    Route::post('payments/robokassa/cancel', ['as' => 'payments.robokassa.cancel', 'uses' => 'RobokassaController@cancel']);

});
