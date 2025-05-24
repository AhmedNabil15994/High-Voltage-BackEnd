<?php

Route::group(['prefix' => 'delivery'], function () {
    Route::get('info', 'FrontEnd\DeliveryChargeController@getDeliveryInfo')->name('frontend.delivery_charges.get_delivery_info');
});
