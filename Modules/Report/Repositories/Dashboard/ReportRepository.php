<?php

namespace Modules\Report\Repositories\Dashboard;

use Modules\Baqat\Entities\BaqatSubscription;
use Modules\Order\Entities\Order;

class ReportRepository
{
    protected $order;
    protected $baqatSubscription;

    public function __construct(Order $order, BaqatSubscription $baqatSubscription)
    {
        $this->order = $order;
        $this->baqatSubscription = $baqatSubscription;
    }

    public function lateOrdersQueryTable($request)
    {
        $query = $this->order->with(['orderTimes', 'driver.driver', 'driverOrderStatuses' => function ($query) {
            $query->where('order_status_id', 9);
        }]);

        if (!empty($request->input('search.value'))) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            });
        }

        $query = $query->whereHas('driverOrderStatuses', function ($query) use ($request) {
            $query->where('order_status_id', 9);
        });

        return $this->filterDataTable($query, $request);
    }

    public function deliveredOrdersQueryTable($request)
    {
        $query = $this->order->with('orderAddress.state');

        $query = $query->whereHas('orderStatus', function ($query) {
            $query->where('flag', 'delivered');
        });

        if ($request->input('search.value')) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere(function ($query) use ($request) {
                    $query->whereHas('orderAddress', function ($query) use ($request) {
                        $query->where('username', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhere('mobile', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhere('email', 'like', '%' . $request->input('search.value') . '%');
                        $query->orWhereHas('state', function ($query) use ($request) {
                            $query->where('title', '%' . $request->input('search.value') . '%');
                        });
                    });
                });
            });
        }

        return $this->filterDeliveredOrdersDataTable($query, $request);
    }

    public function subscriptionsStatusQueryTable($request)
    {
        $query = $this->baqatSubscription->with(['user', 'baqa'])
            ->successSubscriptions();

        if (!empty($request->input('search.value'))) {
            $query = $query->where(function ($query) use ($request) {
                $query->where('id', 'like', '%' . $request->input('search.value') . '%');
            });
        }

        return $this->filterSubscriptionsStatusDataTable($query, $request);
    }

    public function filterSubscriptionsStatusDataTable($query, $request)
    {
        if (isset($request['req']['from']) && $request['req']['from'] != '' && !isset($request['req']['status'])) {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '' && !isset($request['req']['status'])) {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '1') {
            $query->Unexpired();
        }

        if (isset($request['req']['status']) && $request['req']['status'] == '0') {
            $query->ended();
        }

        if (isset($request['req']['state_id']) && !is_null($request['req']['state_id'])) {
            $query->whereHas('user.addresses', function ($query) use ($request) {
                $query->where('addresses.state_id', $request['req']['state_id']);
            });
        }

        return $query;
    }

    public function filterDataTable($query, $request)
    {
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        if (isset($request['req']['driver_id']) && !empty($request['req']['driver_id'])) {
            $query->whereHas('driver', function ($q) use ($request) {
                $q->where('user_id', $request['req']['driver_id']);
            });
        }

        return $query;
    }

    public function filterDeliveredOrdersDataTable($query, $request)
    {
        if (isset($request['req']['payment_status']) && $request['req']['payment_status'] != '') {
            $paymentStatus = $request['req']['payment_status'];
            if ($paymentStatus == 'paid') {
                $query->whereHas('paymentStatus', function ($query) {
                    $query->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
                    $query->whereNotNull('orders.payment_confirmed_at');
                });
            } elseif ($paymentStatus == 'unpaid') {
                $query->where(function ($query) {
                    $query->whereNull('payment_status_id');
                    $query->orWhereHas('paymentStatus', function ($query) {
                        $query->where('flag', 'pending');
                        $query->orWhere(function ($query) {
                            $query->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
                            $query->whereNull('orders.payment_confirmed_at');
                        });
                    });
                });
            }
        }

        if (isset($request['req']['user_id']) && !empty($request['req']['user_id'])) {
            $query->where('user_id', $request['req']['user_id']);
        }

        return $query;
    }

}
