<?php

Route::group(['prefix' => 'order-request', 'middleware' => 'auth'], function () {
    Route::get('start', 'FrontEnd\OrderRequestController@index')->name('frontend.order_request.start');
    Route::post('start/store', 'FrontEnd\OrderRequestController@saveStartOrderRequest')->name('frontend.order_request.save_start_order_request');

});

Route::group(['prefix' => 'orders'], function () {

    Route::group(['prefix' => 'pay'], function () {
        Route::get('success', 'FrontEnd\OrderController@payOrderSuccess')->name('frontend.orders.pay.success');
        Route::get('failed', 'FrontEnd\OrderController@payOrderFailed')->name('frontend.orders.pay.failed');
        Route::post('webhooks', 'FrontEnd\OrderController@payOrderWebhooks')->name('frontend.orders.pay.webhooks');
    });

    Route::group(['prefix' => 'myfatoorah'], function () {
        Route::get('success', 'FrontEnd\OrderController@myfatoorahSuccess')->name('frontend.myfatoorah.orders.success');
        Route::get('failed', 'FrontEnd\OrderController@myfatoorahFailed')->name('frontend.myfatoorah.orders.failed');
    });

    Route::get('/', 'FrontEnd\OrderController@index')->name('frontend.orders.index')->middleware('auth');
    Route::get('{id}', 'FrontEnd\OrderController@show')->name('frontend.orders.show')->middleware('auth');

    Route::group(['middleware' => 'auth'], function () {
        Route::post('/store', 'FrontEnd\OrderController@createOrder')->name('frontend.orders.create_order');
        Route::post('payment/store/{id}', 'FrontEnd\OrderController@payOrder')->name('frontend.orders.payment.store');
    });
});

Route::group(['prefix' => 'working-times'], function () {

    Route::get('get-delivery-days', 'FrontEnd\OrderRequestController@getDeliveryDays')->name('frontend.working_times.get_delivery_days');

});
