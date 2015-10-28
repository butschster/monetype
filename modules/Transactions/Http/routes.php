<?php

Route::get('payments/paypal/send', ['as' => 'payments.paypal.send', 'uses' => 'PayPalController@send']);
Route::get('payments/paypal/success', ['as' => 'payments.paypal.success', 'uses' => 'PayPalController@success']);
Route::get('payments/paypal/cancel', ['as' => 'payments.paypal.cancel', 'uses' => 'PayPalController@cancel']);
