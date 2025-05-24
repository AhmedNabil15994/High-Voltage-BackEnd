<?php

Route::group(['prefix' => 'shopping-cart', 'middleware' => 'auth', 'CheckDeliveryChargeCondition'], function () {

    /* Route::get('/', 'FrontEnd\ShoppingCartController@index')
    ->name('frontend.shopping-cart.index');

    Route::get('delete/{id}', 'FrontEnd\ShoppingCartController@delete')
    ->name('frontend.shopping-cart.delete');

    Route::get('deleteByAjax', 'FrontEnd\ShoppingCartController@deleteByAjax')
    ->name('frontend.shopping-cart.deleteByAjax');

    Route::get('clear', 'FrontEnd\ShoppingCartController@clear')
    ->name('frontend.shopping-cart.clear');

    Route::get('total', 'FrontEnd\ShoppingCartController@totalCart')
    ->name('frontend.shopping-cart.total');

    Route::post('{product?}/{variantPrdId?}', 'FrontEnd\ShoppingCartController@createOrUpdate')
    ->name('frontend.shopping-cart.create-or-update'); */

    Route::get('order-summary', 'FrontEnd\ShoppingCartController@getOrderSummary')->name('frontend.shopping-cart.order_summary');
    Route::post('{id}', 'FrontEnd\ShoppingCartController@createOrUpdate')->name('frontend.shopping-cart.create-or-update');

});
