<?php

Route::group(['prefix' => 'checkout'], function () {

    Route::get('/{id}', 'FrontEnd\CheckoutController@index')->name('frontend.checkout.index');
    // ->middleware(['empty.cart']);

});
