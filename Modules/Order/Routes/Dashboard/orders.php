<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders'], function () {

    Route::get('{id}/{flag}', 'Dashboard\OrderController@show')
        ->name('dashboard.orders.show')
        ->middleware(['permission:show_orders']);

    Route::put('refund/{id}', 'Dashboard\OrderController@refundOrder')
        ->name('dashboard.orders.refund')
        ->middleware(['permission:refund_order']);

    Route::put('admin-note/{id}', 'Dashboard\OrderController@updateAdminNote')
        ->name('dashboard.orders.admin.note')
        ->middleware(['permission:edit_orders']);

    Route::put('client-note/{id}', 'Dashboard\OrderController@updateClientNote')
        ->name('dashboard.orders.client.note')
        ->middleware(['permission:edit_orders']);

    Route::get('payment/confirm/{id}', 'Dashboard\OrderController@confirmPayment')
        ->name('dashboard.orders.confirm.payment')
        ->middleware(['permission:confirm_payment_order']);

    Route::get('payment/cancel/{id}', 'Dashboard\OrderController@cancelPayment')
        ->name('dashboard.orders.cancel.payment')
        ->middleware(['permission:cancel_payment_order']);

    Route::get('change-status/is-ready/{id}', 'Dashboard\OrderController@changeOrderStatusToReady')
        ->name('dashboard.orders.change_order_status_to_ready')
        ->middleware(['permission:show_order_change_status_tab']);

    Route::get('change-status/processing/{id}', 'Dashboard\OrderController@changeOrderStatusToProcessing')
    ->name('dashboard.orders.change_order_status_to_processing')
    ->middleware(['permission:show_order_change_status_tab']);

    Route::get('change-status/received/{id}', 'Dashboard\OrderController@changeOrderStatusToreceived')
    ->name('dashboard.orders.change_order_status_to_received')
    ->middleware(['permission:show_order_change_status_tab']);

    Route::get('change-status/delivered/{id}', 'Dashboard\OrderController@changeOrderStatusToDelivered')
    ->name('dashboard.orders.change_order_status_to_delivered')
    ->middleware(['permission:show_order_change_status_tab']);

    Route::get('change-status/on_the_way/{id}', 'Dashboard\OrderController@changeOrderStatusToOnTheWay')
    ->name('dashboard.orders.change_order_status_to_on_the_way')
    ->middleware(['permission:show_order_change_status_tab']);


    Route::get('exports/{pdf}', 'Dashboard\OrderController@export')
        ->name('dashboard.orders.export')
        ->middleware(['permission:show_orders']);

    Route::get('{id}/edit/order', 'Dashboard\OrderController@edit')
        ->name('dashboard.orders.edit')
        ->middleware(['permission:edit_orders']);

    Route::put('update/order/{id}', 'Dashboard\OrderController@update')
        ->name('dashboard.orders.update')
        ->middleware(['permission:edit_orders']);
});

Route::group(['prefix' => 'create-order'], function () {

    Route::get('/', 'Dashboard\OrderController@create')
        ->name('dashboard.orders.create')
        ->middleware(['permission:add_orders']);

    Route::post('store', 'Dashboard\OrderController@store')
        ->name('dashboard.orders.store')
        ->middleware(['permission:add_orders']);
});

Route::group(['prefix' => 'current-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@index')
        ->name('dashboard.current_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@currentOrdersDatatable')
        ->name('dashboard.orders.datatable')
        ->middleware(['permission:show_orders']);

    Route::put('{id}', 'Dashboard\OrderController@updateOrderStatus')
        ->name('dashboard.orders.update_order_status')
        ->middleware(['permission:edit_orders']);

    Route::get('bulk/update-order-status', 'Dashboard\OrderController@updateBulkOrderStatus')
        ->name('dashboard.orders.update_bulk_order_status')
        ->middleware(['permission:edit_orders']);

    Route::delete('{id}', 'Dashboard\OrderController@destroy')
        ->name('dashboard.orders.destroy')
        ->middleware(['permission:delete_orders']);

    Route::get('deletes', 'Dashboard\OrderController@deletes')
        ->name('dashboard.orders.deletes')
        ->middleware(['permission:delete_orders']);

    Route::get('print/selected-items', 'Dashboard\OrderController@printSelectedItems')
        ->name('dashboard.orders.print_selected_items')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'all-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getAllOrders')
        ->name('dashboard.all_orders.index')
        ->middleware(['permission:show_all_orders']);

    Route::get('datatable', 'Dashboard\OrderController@allOrdersDatatable')
        ->name('dashboard.all_orders.datatable')
        ->middleware(['permission:show_all_orders']);
});

Route::group(['prefix' => 'completed-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getCompletedOrders')
        ->name('dashboard.completed_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@completedOrdersDatatable')
        ->name('dashboard.completed_orders.datatable')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'new-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getNewOrders')
        ->name('dashboard.new_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@newOrdersDatatable')
        ->name('dashboard.new_orders.datatable')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'inprogress-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getInprogressOrders')
        ->name('dashboard.inprogress_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@inprogressOrdersDatatable')
        ->name('dashboard.inprogress_orders.datatable')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'ready-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getReadyOrders')
        ->name('dashboard.ready_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@readyOrdersDatatable')
        ->name('dashboard.ready_orders.datatable')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'inway-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getInwayOrders')
        ->name('dashboard.inway_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@inwayOrdersDatatable')
        ->name('dashboard.inway_orders.datatable')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'delivered-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getDeliveredOrders')
        ->name('dashboard.delivered_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@deliveredOrdersDatatable')
        ->name('dashboard.delivered_orders.datatable')
        ->middleware(['permission:show_orders']);
});

/* Route::group(['prefix' => 'not-completed-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getNotCompletedOrders')
        ->name('dashboard.not_completed_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@notCompletedOrdersDatatable')
        ->name('dashboard.not_completed_orders.datatable')
        ->middleware(['permission:show_orders']);
});

Route::group(['prefix' => 'refunded-orders'], function () {

    Route::get('/', 'Dashboard\OrderController@getRefundedOrders')
        ->name('dashboard.refunded_orders.index')
        ->middleware(['permission:show_orders']);

    Route::get('datatable', 'Dashboard\OrderController@refundedOrdersDatatable')
        ->name('dashboard.refunded_orders.datatable')
        ->middleware(['permission:show_orders']);
}); */
