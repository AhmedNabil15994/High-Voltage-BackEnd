<?php

namespace Modules\Order\Repositories\FrontEnd;

use Auth;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\OrderStatusesHistory;
use Modules\Order\Entities\PaymentStatus;
use Modules\Order\Traits\OrderCalculationTrait;
use Modules\User\Repositories\FrontEnd\AddressRepository;
use Modules\Variation\Entities\ProductVariant;

class OrderRepository
{
    use OrderCalculationTrait, ShoppingCartTrait;

    protected $order;
    protected $address;
    protected $variantPrd;

    public function __construct(Order $order, AddressRepository $address, ProductVariant $variantPrd)
    {
        $this->order = $order;
        $this->address = $address;
        $this->variantPrd = $variantPrd;
    }

    public function getAllByUser($order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus', 'paymentStatus'])->where(function ($q) {
            $q->where('user_id', auth()->id());
        })->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getOrdersByType($type, $order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus', 'paymentStatus']);

        if ($type == 'direct_without_pieces') {
            $orders = $orders->directWithoutPieces();
        } elseif ($type == 'direct_with_pieces') {
            $orders = $orders->directWithPieces();
        }
        $orders = $orders->where(function ($q) {
            $q->where('user_id', auth()->id());
        })->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getUnpaidOrders($order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus', 'paymentStatus']);
        $orders = $orders->where(function ($q) {
            $q->where('user_id', auth()->id());
        });
        $orders = $orders->where(function ($query) {
            $query->whereNull('payment_status_id');
            $query->orWhereHas('paymentStatus', function ($query) {
                $query->where('flag', 'pending');
                $query->orWhere('flag', 'failed');
                $query->orWhere(function ($query) {
                    $query->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
                    $query->whereNull('orders.payment_confirmed_at');
                });
            });
        });
        $orders = $orders->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getPaidOrders($order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus', 'paymentStatus']);
        $orders = $orders->where(function ($q) {
            $q->where('user_id', auth()->id());
        });
        $orders = $orders->whereHas('paymentStatus', function ($query) {
            $query->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
            $query->whereNotNull('orders.payment_confirmed_at');
        });
        $orders = $orders->orderBy($order, $sort)->get();
        return $orders;
    }

    public function findUserOrderById($id, $with = [])
    {
        $order = $this->order->where('user_id', auth()->id());
        if (!empty($with)) {
            $order = $order->with($with);
        }
        $order = $order->find($id);
        return $order;
    }

    public function findById($id, $with = [])
    {
        $order = $this->order->where('user_id', auth()->id());
        if (!empty($with)) {
            $order = $order->with($with);
        }
        $order = $order->find($id);
        return $order;
    }

    public function findByIdWithUserId($id)
    {
        $order = $this->order->withDeleted()->with('rate')->where('user_id', auth()->id())->find($id);
        return $order;
    }

    public function findGuestOrderById($id)
    {
        return $this->order->withDeleted()->with('rate')->find($id);
    }

    public function create($request)
    {
        $orderData = $this->calculateTheOrder();

        DB::beginTransaction();

        try {

            $userId = auth()->check() ? auth()->id() : null;
            $orderStatus = 7; // new_order
            $paymentStatus = null;

            $orderCreated = $this->order->create([
                'original_subtotal' => $orderData['original_subtotal'],
                'subtotal' => $orderData['subtotal'],
                'off' => $orderData['off'],
                'shipping' => $orderData['shipping'],
                'total' => $orderData['total'],
                'total_profit' => $orderData['profit'],
                'user_id' => $userId,
                'order_status_id' => $orderStatus,
                'payment_status_id' => $paymentStatus,
                'order_type' => 'direct_with_pieces',
                'is_fast_delivery' => $request->is_fast_delivery == '1',
                'notes' => $request->notes,
                'order_notes' => session()->has('order_notes') ? session()->get('order_notes') : null,
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

            if (!is_null($orderStatus)) {
                $orderCreated->orderStatusesHistory()->sync([$orderStatus => ['user_id' => $userId]]);
            }

            $this->createOrderProducts($orderCreated, $orderData);

            $address = $this->address->findById($request->address_id);
            if ($address) {
                $this->createOrderAddress($orderCreated, $address);
                $orderCreated->update(['state_id' => $address->state_id]);
            }

            DB::commit();
            return $orderCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderDirectWithoutPieces($request, $address, $deliveryPrice)
    {
        DB::beginTransaction();

        try {

            $userId = auth()->id();
            $orderStatus = 7; // new_order
            $orderCreated = $this->order->create([
                'order_type' => 'direct_without_pieces',
                'is_fast_delivery' => $request->is_fast_delivery == '1',
                'shipping' => $deliveryPrice,
                'user_id' => $userId,
                'state_id' => $address->state_id,
                'order_status_id' => $orderStatus,
                'notes' => $request->notes,
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
            $this->createOrderAddress($orderCreated, $address);

            DB::commit();
            return $orderCreated;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function createOrderProducts($orderCreated, $orderData)
    {
        foreach ($orderData['products'] as $product) {

            $orderProduct = $orderCreated->orderProducts()->create([
                'product_id' => $product['product_id'],
                'off' => $product['off'],
                'qty' => $product['quantity'],
                'starch' => isset($product['starch'])? $product['starch'] : null,
                'price' => $product['original_price'],
                'sale_price' => $product['sale_price'],
                'original_total' => $product['original_total'],
                'total' => $product['total'],
                'total_profit' => $product['total_profit'],
                'notes' => $product['notes'] ?? null,
            ]);

            foreach ($product['qty_details'] as $item) {
                $orderCreated->orderCustomAddons()->create([
                    'order_product_id' => $orderProduct->id,
                    'addon_id' => $item['addon_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => floatval($item['price']) * intval($item['qty']),
                ]);
            }

        }
    }

    public function createOrderAddress($orderCreated, $address)
    {
        $orderCreated->orderAddress()->create([
            'username' => $address['username'] ?? optional(auth()->user())->name,
            'email' => $address['email'] ?? (optional(auth()->user())->email ?? null),
            'mobile' => $address['mobile'] ?? (optional(auth()->user())->mobile ?? null),
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
            'latitude' => $address['latitude'] ?? null,
            'longitude' => $address['longitude'] ?? null,
        ]);
    }

    public function updatePayOrder($request)
    {
        $order = $this->findById($request['OrderID']);
        if (!$order) {
            return false;
        }

        $newOrderStatus = $this->getOrderStatusByFlag('new_order')->id; // new_order
        if ($request['Result'] == 'CAPTURED') {
            // $newOrderStatus = $this->getOrderStatusByFlag('new_order')->id; // new_order
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'success')->first())->id ?? $order->payment_status_id;
            $paymentConfirmedAt = date('Y-m-d H:i:s');
        } else {
            /* $newOrderStatus = $this->getOrderStatusByFlag('failed')->id; // failed
            $newPaymentStatus = optional(PaymentStatus::where('flag', 'failed')->first())->id ?? $order->payment_status_id; */
            $newPaymentStatus = null;
            $paymentConfirmedAt = null;
        }

        $data = [
            'payment_status_id' => $newPaymentStatus,
            'payment_confirmed_at' => $paymentConfirmedAt,
            'increment_qty' => true,
        ];

        if (is_null($order->order_status_id)) {
            $data['order_status_id'] = $newOrderStatus;
        }

        $order->update($data);

        // Add new order history
        $order->orderStatusesHistory()->attach([$newOrderStatus => ['user_id' => $order->user_id ?? null]]);

        $order->transactions()->updateOrCreate(
            [
                'transaction_id' => $request['OrderID'],
            ],
            [
                'auth' => $request['Auth'],
                'tran_id' => $request['TranID'],
                'result' => $request['Result'],
                'post_date' => $request['PostDate'],
                'ref' => $request['Ref'],
                'track_id' => $request['TrackID'],
                'payment_id' => $request['PaymentID'],
            ]
        );
        $newPoints = $order->user->loyalty_points_count + (int) $order->total *100;
        $order->user()->update(['loyalty_points_count'=>$newPoints]);
        return ($request['Result'] == 'CAPTURED') ? true : false;
    }

    public function getOrderStatusByFlag($flag)
    {
        return OrderStatus::where('flag', $flag)->first();
    }

}
