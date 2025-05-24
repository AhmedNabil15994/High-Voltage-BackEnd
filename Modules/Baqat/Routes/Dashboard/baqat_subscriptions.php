<?php

Route::group(['prefix' => 'baqat-subscriptions'], function () {

    Route::get('/', 'Dashboard\BaqatSubscriptionController@index')
        ->name('dashboard.baqat_subscriptions.index')
        ->middleware(['permission:show_baqat_subscriptions']);

    Route::get('datatable', 'Dashboard\BaqatSubscriptionController@datatable')
        ->name('dashboard.baqat_subscriptions.datatable')
        ->middleware(['permission:show_baqat_subscriptions']);

    Route::get('create', 'Dashboard\BaqatSubscriptionController@create')
        ->name('dashboard.baqat_subscriptions.create')
        ->middleware(['permission:add_baqat_subscriptions']);

    Route::post('/', 'Dashboard\BaqatSubscriptionController@store')
        ->name('dashboard.baqat_subscriptions.store')
        ->middleware(['permission:add_baqat_subscriptions']);

    /* Route::get('{id}/edit', 'Dashboard\BaqatSubscriptionController@edit')
    ->name('dashboard.baqat_subscriptions.edit')
    ->middleware(['permission:edit_baqat_subscriptions']);

    Route::put('{id}', 'Dashboard\BaqatSubscriptionController@update')
    ->name('dashboard.baqat_subscriptions.update')
    ->middleware(['permission:edit_baqat_subscriptions']); */

    Route::delete('{id}', 'Dashboard\BaqatSubscriptionController@destroy')
        ->name('dashboard.baqat_subscriptions.destroy')
        ->middleware(['permission:delete_baqat_subscriptions']);

    Route::get('deletes', 'Dashboard\BaqatSubscriptionController@deletes')
        ->name('dashboard.baqat_subscriptions.deletes')
        ->middleware(['permission:delete_baqat_subscriptions']);

    Route::get('{id}', 'Dashboard\BaqatSubscriptionController@show')
        ->name('dashboard.baqat_subscriptions.show')
        ->middleware(['permission:show_baqat_subscriptions']);

});

Route::group(['prefix' => 'current-subscriptions'], function () {

    Route::get('/', 'Dashboard\BaqatSubscriptionController@currentIndex')
        ->name('dashboard.baqat_subscriptions.current_index')
        ->middleware(['permission:show_baqat_subscriptions']);

    Route::get('datatable', 'Dashboard\BaqatSubscriptionController@currentDatatable')
        ->name('dashboard.baqat_subscriptions.current_datatable')
        ->middleware(['permission:show_baqat_subscriptions']);

});
