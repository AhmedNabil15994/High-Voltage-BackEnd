<?php

Route::get('offers', 'FrontEnd\CouponController@index')->name('frontend.offers.index');

Route::group(['prefix' => 'coupons'], function () {
    Route::post('/check_coupon', 'FrontEnd\CouponController@checkCoupon')->name('frontend.check_coupon');
});

Route::group(['prefix' => 'coupons', 'middleware' => 'auth'], function () {
    Route::post('orders/{orderId}/apply-coupon', 'FrontEnd\CouponController@applyCouponOnOrder')->name('frontend.coupons.apply_coupon_on_order');
});
