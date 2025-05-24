<?php

Route::group(['prefix' => 'packages'], function () {

    Route::get('index', 'FrontEnd\BaqatController@index')->name('frontend.baqat.index');

    Route::group(['middleware' => 'auth'], function () {

        Route::get('{id}/purchase-package', 'FrontEnd\BaqatController@show')->name('frontend.baqat.show');
        Route::post('store/{id}/purchase-package', 'FrontEnd\BaqatSubscriptionController@store')->name('frontend.baqat.purchase_package');

        Route::group(['prefix' => 'pay'], function () {
            Route::get('success', 'FrontEnd\BaqatSubscriptionController@subscriptionSuccess')->name('frontend.baqat_subscriptions.success');
            Route::get('failed', 'FrontEnd\BaqatSubscriptionController@subscriptionFailed')->name('frontend.baqat_subscriptions.failed');
            Route::post('webhooks', 'FrontEnd\BaqatSubscriptionController@subscriptionWebhooks')->name('frontend.baqat_subscriptions.webhooks');
        });
    });
});
