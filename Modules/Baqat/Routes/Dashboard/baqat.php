<?php

Route::group(['prefix' => 'baqat'], function () {

    Route::get('/', 'Dashboard\BaqatController@index')
        ->name('dashboard.baqat.index')
        ->middleware(['permission:show_baqat']);

    Route::get('datatable', 'Dashboard\BaqatController@datatable')
        ->name('dashboard.baqat.datatable')
        ->middleware(['permission:show_baqat']);

    Route::get('create', 'Dashboard\BaqatController@create')
        ->name('dashboard.baqat.create')
        ->middleware(['permission:add_baqat']);

    Route::post('/', 'Dashboard\BaqatController@store')
        ->name('dashboard.baqat.store')
        ->middleware(['permission:add_baqat']);

    Route::get('{id}/edit', 'Dashboard\BaqatController@edit')
        ->name('dashboard.baqat.edit')
        ->middleware(['permission:edit_baqat']);

    Route::put('{id}', 'Dashboard\BaqatController@update')
        ->name('dashboard.baqat.update')
        ->middleware(['permission:edit_baqat']);

    Route::delete('{id}', 'Dashboard\BaqatController@destroy')
        ->name('dashboard.baqat.destroy')
        ->middleware(['permission:delete_baqat']);

    Route::get('deletes', 'Dashboard\BaqatController@deletes')
        ->name('dashboard.baqat.deletes')
        ->middleware(['permission:delete_baqat']);

    Route::get('{id}', 'Dashboard\BaqatController@show')
        ->name('dashboard.baqat.show')
        ->middleware(['permission:show_baqat']);

});

Route::get('baqat/calculate/end-date', 'Dashboard\BaqatController@calculateEndDate')->name('dashboard.baqat.calculate_end_date');
