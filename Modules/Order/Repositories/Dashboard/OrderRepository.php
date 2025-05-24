<?php

namespace Modules\Order\Repositories\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Traits\OrderCalculationTrait;
use Modules\User\Repositories\Dashboard\AddressRepository as AddressRepo;

class OrderRepository
{
    use OrderCalculationTrait, ShoppingCartTrait;

    protected $order;
    protected $address;

    public function __construct(Order $order, AddressRepo $address)
    {
        $this->order = $order;
        $this->address = $address;
    }

    public function monthlyOrders()
    {
        $data["orders_dates"] = $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->where('flag', 'success');
            })
            ->select(DB::raw("DATE_FORMAT(payment_confirmed_at,'%Y-%m') as date"))
            ->groupBy(DB::raw("DATE_FORMAT(payment_confirmed_at,'%Y-%m')"))
            ->pluck('date');

        $ordersIncome = $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->whereIn('flag', ['success','cash']);
            })
            ->select(DB::raw("sum(total) as profit"))
            ->groupBy(DB::raw("DATE_FORMAT(payment_confirmed_at, '%Y-%m')"))
            ->get();

        $data["profits"] = json_encode(array_column($ordersIncome->toArray(), 'profit'));

        return $data;
    }

    public function ordersType()
    {
        $orders = $this->order
            ->with('orderStatus')
            ->select("order_status_id", DB::raw("count(id) as count"))
            ->groupBy('order_status_id')
            ->get();

        foreach ($orders as $order) {

            $status = $order->orderStatus->title;
            $order->type = $status;
        }

        $data["ordersCount"] = json_encode(array_column($orders->toArray(), 'count'));
        $data["ordersType"] = json_encode(array_column($orders->toArray(), 'type'));

        return $data;
    }

    public function totalTodayProfit()
    {
        return $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->whereIn('flag', ['success','cash']);
            })
            ->whereDate("payment_confirmed_at", date("Y-m-d"))
            ->sum('total');
    }

    public function totalMonthProfit()
    {
        return $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->whereIn('flag', ['success','cash']);
            })
            ->whereMonth("payment_confirmed_at", date("m"))
            ->whereYear("payment_confirmed_at", date("Y"))
            ->sum('total');
    }

    public function totalYearProfit()
    {
        return $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->whereIn('flag', ['success','cash']);
            })
            ->whereYear("payment_confirmed_at", date("Y"))
            ->sum('total');
    }

    public function completeOrders()
    {
        $orders = $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->whereIn('flag', ['success','cash','subscriptions_balance','loyalty_points']);
            })->count();

        return $orders;
    }

    public function totalProfit()
    {
        return $this->order/* ->whereHas('orderStatus', function ($query) {
        $query->successOrderStatus();
        }) */    ->whereHas('paymentStatus', function ($query) {
                $query->whereIn('flag', ['success','cash']);
            })->sum('total');
    }

    public function getAll($order = 'id', $sort = 'desc')
    {
        $orders = $this->order->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getOrdersCountByFlag($flag = 'all_orders')
    {
        $query = $this->order->whereNull('deleted_at');
        if ($flag == 'current_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['new_order', 'received', 'processing', 'is_ready', 'on_the_way']);
        } elseif ($flag == 'completed_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['delivered']);
        } elseif ($flag == 'not_completed_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['failed']);
        } elseif ($flag == 'refunded_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['refund']);
        }elseif ($flag == 'new_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['new_order', 'received', 'pending']);
        }elseif ($flag == 'inprogress_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['processing']);
        }elseif ($flag == 'inway_orders') {
            $query = $this->orderStatusRelationByFlag($query, [ 'on_the_way']);
        }elseif ($flag == 'ready_orders') {
            $query = $this->orderStatusRelationByFlag($query, ['is_ready']);
        }
        return $query->count();
    }

    private function orderStatusRelationByFlag($query, $flag = [])
    {
        return $query->whereHas('orderStatus', function ($query) use ($flag) {
            $query->whereIn('flag', $flag);
        });
    }

    public function findById($id)
    {
        $order = $this->order
            ->with([
                'orderProducts.product',
                'orderProducts.orderProductCustomAddons',
                'orderCoupons',
                'orderCustomAddons',
                'orderAddress',
                'driver'
            ])->withDeleted()->find($id);

        return $order;
    }

    public function updateUnread($id)
    {
        $order = $this->findById($id);
        if (!$order) {
            abort(404);
        }

        $order->update([
            'unread' => true,
        ]);
    }

    public function updateOrderStatusAndDriver($request, $id)
    {
        DB::beginTransaction();
        try {
            $order = $this->findById($id);
            if (!$order) {
                abort(404);
            }

            $orderData = ['order_status_id' => $request['order_status']];
            if (isset($request['order_notes']) && !empty($request['order_notes'])) {
                $orderData['order_notes'] = $request['order_notes'];
            }

            $order->update($orderData);
            $order->orderStatusesHistory()->attach([$request['order_status'] => ['user_id' => auth()->id()]]);

            if ($request['user_id']) {
                $order->driver()->delete();
                $order->driver()->updateOrCreate([
                    'user_id' => $request['user_id'],
                ]);
                $order->driverOrderStatuses()->create([
                    'user_id' => $request['user_id'],
                    'order_status_id' => $request['order_status'],
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function restoreSoftDelte($model)
    {
        $model->restore();
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $model = $this->findById($id);

            if ($model->trashed()):
                $model->forceDelete();
            else:
                $model->delete();
            endif;

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function deleteSelected($request)
    {
        DB::beginTransaction();

        try {

            foreach ($request['ids'] as $id) {
                $model = $this->delete($id);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getSelectedOrdersById($ids)
    {
        $orders = $this->order
            ->with([
                'orderProducts.product',
                'orderCoupons',
                'orderCustomAddons',
                'user',
                'orderAddress',
                'driver',
                'transactions',
            ]);

        $orders = $orders->whereIn('id', $ids)->get();
        return $orders;
    }

    public function customQueryTable($request, $flags = [])
    {
        $query = $this->order->with('orderAddress.state');

        if (!empty($flags)) {
            $query = $query->whereHas('orderStatus', function ($query) use ($flags) {
                $query->whereIn('flag', $flags);
            });
        }

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
        return $this->filterDataTable($query, $request);
    }

    public function filterDataTable($query, $request)
    {
        if (isset($request['req']['from']) && $request['req']['from'] != '') {
            $query->whereDate('created_at', '>=', $request['req']['from']);
        }

        if (isset($request['req']['to']) && $request['req']['to'] != '') {
            $query->whereDate('created_at', '<=', $request['req']['to']);
        }

        /* if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'only')
        $query->onlyDeleted();

        if (isset($request['req']['deleted']) && $request['req']['deleted'] == 'with')
        $query->withDeleted();

        if (isset($request['req']['status']) && $request['req']['status'] == '1')
        $query->active();

        if (isset($request['req']['status']) && $request['req']['status'] == '0')
        $query->unactive(); */

        if (isset($request['req']['vendor']) && !empty($request['req']['vendor'])) {
            $query->whereHas('vendors', function ($q) use ($request) {
                $q->where('order_vendors.vendor_id', $request['req']['vendor']);
            });
        }

        if (isset($request['req']['order_status']) && !empty($request['req']['order_status'])) {
            $query->whereHas('orderStatus', function ($q) use ($request) {
                $q->where('id', $request['req']['order_status']);
            });
        }

        if (isset($request['req']['state_id']) && !empty($request['req']['state_id'])) {
            $query->whereHas('orderAddress.state', function ($q) use ($request) {
                $q->where('id', $request['req']['state_id']);
            });
        }

        if (isset($request['req']['city_id']) && !empty($request['req']['city_id'])) {
            $query->whereHas('orderAddress.state.city', function ($q) use ($request) {
                $q->where('id', $request['req']['city_id']);
            });
        }

        if (isset($request['req']['country_id']) && !empty($request['req']['country_id'])) {
            $query->whereHas('orderAddress.state.city.country', function ($q) use ($request) {
                $q->where('id', $request['req']['country_id']);
            });
        }

        if (isset($request['req']['driver_id']) && !empty($request['req']['driver_id'])) {
            $query->whereHas('driver', function ($q) use ($request) {
                $q->where('user_id', $request['req']['driver_id']);
            });
        }

        if (isset($request['req']['payment_type']) && !empty($request['req']['payment_type'])) {

            $query->whereHas('transactions', function ($q) use ($request) {
                if ($request['req']['payment_type'] == 'online') {
                    $q->whereIn('method', ['knet', 'cc']);
                } else {
                    $q->where('method', $request['req']['payment_type']);
                }
            })->orWhereHas('paymentStatus', function ($q) use ($request) {
                $q->where('flag', $request['req']['payment_type'] == 'online' ? 'success' : $request['req']['payment_type']);
            });
        }

        if (isset($request['req']['payment_status']) && !empty($request['req']['payment_status'])) {

            if ($request['req']['payment_status'] == 'success') {
                $query->whereNotNull('payment_confirmed_at');
            } else {
                if ($request['req']['payment_status'] == 'pending') {
                    $query->whereNull('payment_status_id');
                    $query->orWhereHas('paymentStatus', function ($q) use ($request) {
                        $q->where('flag', 'pending');
                    });
                }else if ($request['req']['payment_status'] == 'not_paid') {
                    $query->whereNull('payment_status_id');
                }else{
                    $query->whereHas('paymentStatus', function ($q) use ($request) {
                        $q->where('flag', $request['req']['payment_status']);
                    });
                }

            }
        }

        if (isset($request['req']['user_id']) && !empty($request['req']['user_id'])) {
            $query->where('user_id', $request['req']['user_id']);
        }

        if (isset($request['req']['order_type']) && !empty($request['req']['order_type'])) {
            $query->where('order_type', $request['req']['order_type']);
        }

        return $query;
    }

    public function getOnlinePendingOrders()
    {
        $currentDate = new \DateTime;
        $currentDate->modify('-15 minutes');
        $formattedDate = $currentDate->format('Y-m-d H:i:s');
        $orders = $this->order->where('payment_status_id', 1);
        // get order after 15 minutes
        $orders = $orders->whereHas('transactions', function ($query) use ($formattedDate) {
            $query->where('created_at', '<=', $formattedDate);
        });
        return $orders->get();
    }

    public function refundOrderOperation($request, $id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $refund = $order->total;

            $refund = $this->refundItem($order, $request);

            if (is_array($refund) && $refund[0] == 0) {
                return $refund;
            }

            $order->subRefund($refund);
            $order->load("orderProducts");

            if ($order->orderProducts->count() == 0) {
                $order_status_id = optional(OrderStatus::where('flag', 'refund')->first())->id ?? $order->order_status_id;
                $order->update(["order_status_id" => $order_status_id]);
                $order->orderStatusesHistory()->attach([$order_status_id => ['user_id' => auth()->id()]]);
            }

            DB::commit();

            return [1, "order" => $order->load(["user", "orderStatus"]), "refund" => $refund];
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function refundItem($order, $request)
    {
        $total_refund = 0;
        foreach ($request->items as $id => $item) {
            # code...
            $query = $order->orderProducts();
            $product = $query->where("id", $id)->first();

            if ($product) {

                if ($product->qty < $item["qty"]) {
                    return [0, __('order::dashboard.orders.show.refund_validation.large_qty_than_item_qty', ['product' => $product->product_title . ' #' . $product->id])];
                }

                if ($product->qty > $item["qty"]) {
                    $total_refund += $product->refundOperation($item["qty"], ($request->increment_stock ? true : false));
                }
            }
        }
        return $total_refund;
    }

    public function confirmPayment($order)
    {
        DB::beginTransaction();

        try {

            // if (in_array(optional($order->paymentStatus)->flag, ['pending', 'cash'])) {
            if (is_null($order->payment_confirmed_at)) {
                $order->payment_status_id = optional(PaymentStatus::where('flag', 'cash')->first())->id ?? $order->payment_status_id;
                $order->payment_confirmed_at = date('Y-m-d H:i:s');
                $order->save();
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function cancelPayment($id)
    {
        $order = $this->findById($id);
        DB::beginTransaction();

        try {

            if (optional($order->paymentStatus)->flag != 'failed') {
                $order->update([
                    'payment_status_id' => optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $order->payment_status_id,
                    'payment_confirmed_at' => null,
                    'order_status_id' => 4,
                ]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function changeOrderStatusToreceived($id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $orderStatusId = optional(OrderStatus::where('flag', 'received')->first())->id ?? $order->order_status_id;
            $order->order_status_id = $orderStatusId;
            $order->save();
            $order->orderStatusesHistory()->attach([$orderStatusId => ['user_id' => auth()->id()]]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function changeOrderStatusToReady($id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $orderStatusId = optional(OrderStatus::where('flag', 'is_ready')->first())->id ?? $order->order_status_id;
            $order->order_status_id = $orderStatusId;
            $order->save();
            $order->orderStatusesHistory()->attach([$orderStatusId => ['user_id' => auth()->id()]]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function changeOrderStatusToProcessing($id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $orderStatusId = optional(OrderStatus::where('flag', 'processing')->first())->id ?? $order->order_status_id;
            $order->order_status_id = $orderStatusId;
            $order->save();
            $order->orderStatusesHistory()->attach([$orderStatusId => ['user_id' => auth()->id()]]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function changeOrderStatusToOnTheWay($id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $orderStatusId = optional(OrderStatus::where('flag', 'on_the_way')->first())->id ?? $order->order_status_id;
            $order->order_status_id = $orderStatusId;
            $order->save();
            $order->orderStatusesHistory()->attach([$orderStatusId => ['user_id' => auth()->id()]]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function changeOrderStatusToDelivered($id)
    {
        $order = $this->findById($id);

        DB::beginTransaction();

        try {
            $orderStatusId = optional(OrderStatus::where('flag', 'delivered')->first())->id ?? $order->order_status_id;
            $order->order_status_id = $orderStatusId;
            $order->save();
            $order->orderStatusesHistory()->attach([$orderStatusId => ['user_id' => auth()->id()]]);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderDirectWithoutPieces($request, $address, $deliveryPrice, $user)
    {
        DB::beginTransaction();

        try {

            $userId = $user->id;
            $orderStatus = 7; // new_order
            $orderCreated = $this->order->create([
                'order_type' => 'direct_without_pieces',
                'is_fast_delivery' => $request->is_fast_delivery == '1',
                'shipping' => $deliveryPrice,
                'user_id' => $userId,
                'state_id' => $address->state_id,
                'order_status_id' => $orderStatus,
                'order_added_by' => 'dashboard',
            ]);
            $receivingData = [
                'receiving_date' => $request->receiving_date,
                'receiving_time' => $request->receiving_time,
            ];
            $deliveryData = [
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
            ];
            $orderCreated->orderTimes()->create([
                'receiving_data' => $receivingData,
                'delivery_data' => $deliveryData,
            ]);
            $this->createOrderAddress($orderCreated, $address, $user);

            DB::commit();
            return $orderCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateOrderDirectWithoutPieces($request, $address, $deliveryPrice, $user, $order)
    {
        DB::beginTransaction();

        try {

            $userId = $user->id;
            $order->update([
                'is_fast_delivery' => $request->is_fast_delivery == '1',
                'shipping' => $deliveryPrice,
                'user_id' => $userId,
                'state_id' => $address->state_id,
            ]);
            $receivingData = [
                'receiving_date' => $request->receiving_date,
                'receiving_time' => $request->receiving_time,
            ];
            $deliveryData = [
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
            ];
            $order->orderTimes()->update([
                'receiving_data' => $receivingData,
                'delivery_data' => $deliveryData,
            ]);

            if ($order->orderAddress->address_id != $address->id) {
                $order->orderAddress()->delete();
                $this->createOrderAddress($order, $address, $user);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderAddress($orderCreated, $address, $user)
    {
        $orderCreated->orderAddress()->create([
            'username' => $address['username'] ?? $user->name,
            'email' => $address['email'] ?? ($user->email ?? null),
            'mobile' => $address['mobile'] ?? ($user->mobile ?? null),
            'address' => $address['address'] ?? null,
            'block' => $address['block'] ?? null,
            'street' => $address['street'] ?? null,
            'building' => $address['building'] ?? null,
            'state_id' => $address['state_id'] ?? null,
            'avenue' => $address['avenue'] ?? null,
            'floor' => $address['floor'] ?? null,
            'flat' => $address['flat'] ?? null,
            'automated_number' => $address['automated_number'] ?? null,
            'address_id' => $address['id'] ?? null,
        ]);
    }

    public function createOrderData($request, $allProductAddons, $deliveryChargePrice, $subtotal, $user)
    {
        DB::beginTransaction();

        try {

            $orderStatus = 7; // new_order
            $userId = $user->id;

            $orderCreated = $this->order->create([
                'original_subtotal' => $subtotal,
                'subtotal' => $subtotal,
                'off' => 0,
                'shipping' => $deliveryChargePrice,
                'total' => $subtotal + $deliveryChargePrice,
                'total_profit' => 0,
                'user_id' => $userId,
                'order_status_id' => $orderStatus,
                'payment_status_id' => null,
                'order_type' => $request->order_type,
                'is_fast_delivery' => $request->is_fast_delivery == '1',
                'order_added_by' => 'dashboard',
            ]);

            $receivingData = [
                'receiving_date' => $request->receiving_date,
                'receiving_time' => $request->receiving_time,
                'receiving_time_format_type' => $request->receiving_time_format_type,
            ];
            $deliveryData = [
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
                'delivery_time_format_type' => $request->delivery_time_format_type,
            ];
            $orderCreated->orderTimes()->create([
                'receiving_data' => $receivingData,
                'delivery_data' => $deliveryData,
            ]);

            if (!is_null($orderStatus)) {
                $orderCreated->orderStatusesHistory()->sync([$orderStatus => ['user_id' => auth()->id()]]);
            }

            $this->createOrderProducts($allProductAddons, $orderCreated);

            $address = $this->address->findById($request->address_id);
            if ($address) {
                $this->createOrderAddress($orderCreated, $address, $user);
                $orderCreated->update(['state_id' => $address->state_id]);
            }

            DB::commit();

            return $orderCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateOrderData($request, $address, $allProductAddons, $deliveryChargePrice, $subtotal, $user, $order)
    {
        DB::beginTransaction();

        try {

            $order->update([
                'original_subtotal' => $subtotal,
                'subtotal' => $subtotal,
                'off' => 0,
                'shipping' => $deliveryChargePrice,
                'total' => $subtotal + $deliveryChargePrice,
                'total_profit' => 0,
                /* 'order_status_id' => '',
                'payment_status_id' => '', */
                'order_type' => $request->order_type,
                'is_fast_delivery' => $request->is_fast_delivery == '1',
                'order_added_by' => 'dashboard',
            ]);

            $receivingData = [
                'receiving_date' => $request->receiving_date,
                'receiving_time' => $request->receiving_time,
                'receiving_time_format_type' => $request->receiving_time_format_type,
            ];
            $deliveryData = [
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_time,
                'delivery_time_format_type' => $request->delivery_time_format_type,
            ];
            $order->orderTimes()->update([
                'receiving_data' => $receivingData,
                'delivery_data' => $deliveryData,
            ]);

            /* if (!is_null($orderStatus)) {
            $orderCreated->orderStatusesHistory()->sync([$orderStatus => ['user_id' => auth()->id()]]);
            } */

            $order->orderCustomAddons()->delete();
            $order->orderProducts()->delete();
            $this->createOrderProducts($allProductAddons, $order);

            if ($order->orderAddress->address_id != $address->id) {
                $order->orderAddress()->delete();
                $this->createOrderAddress($order, $address, $user);
                $order->update(['state_id' => $address->state_id]);
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderProducts($allProductAddons, $orderCreated)
    {
        foreach ($allProductAddons as $productId => $product) {
            if (isset($product['addons']) && !empty($product['addons'])) {
                $originPrice = array_sum(array_column($product['addons'], 'total'));
                $orderProduct = $orderCreated->orderProducts()->create([
                    'product_id' => $productId,
                    'off' => $product['off'],
                    'qty' => $product['qty'],
                    'starch' => isset($product['starch'])? $product['starch'] : null,
                    'price' => $originPrice,
                    'sale_price' => $originPrice,
                    'original_total' => $originPrice,
                    'total' => $originPrice,
                    'total_profit' => 0,
                ]);
                foreach ($product['addons'] as $k => $addon) {
                    $orderCreated->orderCustomAddons()->create([
                        'order_product_id' => $orderProduct->id,
                        'addon_id' => $addon['addon_id'],
                        'qty' => $addon['qty'],
                        'price' => $addon['price'],
                        'total' => $addon['total'],
                    ]);
                }
            }
        }
    }
}
