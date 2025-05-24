<?php
Route::group(["namespace" => "Dashboard"], function () {

    Route::group(['prefix' => 'reports'], function () {

        Route::get('/', 'ReportController@getOrderReports')
            ->name('dashboard.reports.get_order_reports')
            ->middleware(['permission:show_reports']);

        Route::group(['prefix' => 'late-orders'], function () {

            Route::get('/', 'ReportController@getLateOrderReports')
                ->name('dashboard.reports.late_orders.index')
                ->middleware(['permission:show_reports']);

            Route::get('datatable', 'ReportController@lateOrderDatatable')
                ->name('dashboard.reports.late_orders.datatable')
                ->middleware(['permission:show_reports']);

        });

        Route::group(['prefix' => 'subscriptions-status'], function () {

            Route::get('/', 'ReportController@getSubscriptionsStatusList')
                ->name('dashboard.reports.subscriptions_status.index')
                ->middleware(['permission:show_reports']);

            Route::get('datatable', 'ReportController@subscriptionsStatusDatatable')
                ->name('dashboard.reports.subscriptions_status.datatable')
                ->middleware(['permission:show_reports']);

        });

        Route::group(['prefix' => 'delivered-orders'], function () {

            Route::get('/', 'ReportController@getDeliveredOrdersReports')
                ->name('dashboard.reports.delivered_orders.index')
                ->middleware(['permission:show_reports']);

            Route::get('datatable', 'ReportController@deliveredOrdersDatatable')
                ->name('dashboard.reports.delivered_orders.datatable')
                ->middleware(['permission:show_reports']);

        });
    });

});
