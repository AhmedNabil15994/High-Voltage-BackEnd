<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'working-times', 'namespace' => 'Dashboard'], function () {

    Route::get('/', 'WorkingTimeController@index')
        ->name('dashboard.working_times.index')
        ->middleware(['permission:show_working_times']);

    Route::post('store', 'WorkingTimeController@store')
        ->name('dashboard.working_times.store')
        ->middleware(['permission:show_working_times']);
});
