<?php

Route::group(['middleware' => ['auth', 'CheckDeliveryChargeCondition']], function () {

    Route::get('request-pieces', 'FrontEnd\CategoryController@getCategoriesWithProducts')->name('frontend.categories.products');

});
