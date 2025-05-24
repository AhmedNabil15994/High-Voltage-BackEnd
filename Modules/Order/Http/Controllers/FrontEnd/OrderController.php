<?php

namespace Modules\Order\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Catalog\Entities\ProductCustomAddon;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Notification\Repositories\Dashboard\NotificationRepository as Notification;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Repositories\FrontEnd\OrderRepository as Order;
use Modules\Order\Traits\OrderTrait;
use Modules\Transaction\Services\MyFatoorahPaymentService;
use Modules\Transaction\Services\UPaymentService;
use Modules\User\Entities\LoyaltyPointBalanceLog;
use Modules\User\Entities\SubscriptionBalanceLog;

//use Modules\Transaction\Services\PaymentService;
//use Modules\Transaction\Services\UPaymentTestService;

class OrderController extends Controller
{
    use ShoppingCartTrait, OrderTrait;

    protected $payment;
    protected $myFatoorahPayment;
    protected $order;
    protected $product;
    protected $notification;

    public function __construct(
        Order $order,
        UPaymentService $payment,
        MyFatoorahPaymentService $myFatoorahPayment,
        Product $product,
        Notification $notification
    ) {
        $this->payment = $payment;
        $this->myFatoorahPayment = $myFatoorahPayment;
        $this->order = $order;
        $this->product = $product;
        $this->notification = $notification;
    }

    public function index()
    {
        $unpaidOrders = $this->order->getUnpaidOrders();
        $paidOrders = $this->order->getPaidOrders();
        return view('order::frontend.orders.index', compact('unpaidOrders', 'paidOrders'));
    }

    public function show($id)
    {
        $order = $this->order->findUserOrderById($id, ['orderStatus', 'orderCoupons', 'orderCustomAddons', 'orderProducts.orderProductCustomAddons', 'orderProducts.product']);
        if (!$order) {
            abort(404);
        }

        return view('order::frontend.orders.show', compact('order'));
    }

    public function createOrder(Request $request)
    {
        $userToken = auth()->id();
        $request->request->add(json_decode(get_cookie_value('DIRECT_ORDER_COOKIE_' . $userToken), true) ?? []);

        $v = Validator::make($request->all(), $rules = [
            'address_id' => 'required|exists:addresses,id',
            'accept_terms_conditions' => 'required',
            'receiving_date' => 'required|date_format:Y-m-d',
            'delivery_date' => 'required|date_format:Y-m-d|after_or_equal:receiving_date',
            'receiving_time' => 'required|string',
            'delivery_time' => 'required|string',
            'order_type' => 'required|in:direct_with_pieces,direct_without_pieces',
            'notes' => 'nullable|string|max:3000',
        ]);


        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }

        if (getCartContent()->count() == 0) {
            return redirect()->back()->withErrors(__('Choose items firstly!'));
        }

        if (!is_null($request->receiving_date)) {
            if ($request->receiving_date < date('Y-m-d')) {
                return redirect()->back()->withErrors(__('Pick-up day is not available, choose another day'));
            }
        }

        if (!is_null($request->delivery_date)) {
            if ($request->delivery_date < date('Y-m-d')) {
                return redirect()->back()->withErrors(__('Delivery day is not available, choose another day'));
            }
        }

        if (!is_null($request->receiving_time) && $request->receiving_date == date('Y-m-d')) {
            $timeFrom = explode('-', $request->receiving_time)[0];
            $timeTo = explode('-', $request->receiving_time)[1];
            if (now()->format('H:i') >= $timeTo) {
                return redirect()->back()->withErrors(__('Pick-up time is currently not available, choose another time'));
            }
        }

        if (!is_null($request->delivery_time) && $request->delivery_date == date('Y-m-d')) {
            $timeFrom = explode('-', $request->delivery_time)[0];
            $timeTo = explode('-', $request->delivery_time)[1];

            if (now()->format('H:i') >= $timeTo) {
                return redirect()->back()->withErrors(__('Delivery time is currently not available, choose another time'));
            }
        }

        if (auth()->user()->unPaidOrders->count() > 0) {
            return redirect()->back()->withInput()->with(['unpaid_orders_error' => __('Your are not place order due to previous order not paid, do you want to go to payment page?'), 'alert' => 'danger']);
        }
        foreach (getCartContent() as $key => $item) {
            $cartProduct = $item->attributes->product;
            $product = $this->product->findById($cartProduct->id);
            if (!$product) {
                return redirect()->back()->withErrors(__('cart::api.cart.product.not_found') . $cartProduct->id);
            }

            // check addons validation
            $allQtyCheck = $this->checkAddonsValidation($product->id, $item->attributes['qty_details']);
            if (gettype($allQtyCheck) == 'string') {
                return redirect()->back()->withErrors($allQtyCheck);
            }
        }
        $order = $this->order->create($request);
        if (!$order) {
            return redirect()->back()->with([
                'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.order_failed'),
            ]);
        }

        $this->assignDriverToOrder($request->all(),$order->id);

        $this->fireLog($order);
        $this->sendNotificationToDrivers($order);
        $this->clearCart($userToken);
        set_cookie_value('DIRECT_ORDER_COOKIE_' . $userToken, null);
        session()->forget('order_notes');
        return view('core::frontend.components._success_order_redirect', ['successMessage' => __('The request has been sent successfully!')]);
    }

    public function redirectToFailedPayment($orderId)
    {
        return redirect()->route('frontend.checkout.index', $orderId)->with([
            'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.order_failed'),
        ]);
    }

    public function fireLog($order)
    {
        $dashboardUrl = LaravelLocalization::localizeUrl(url(route('dashboard.orders.show', [$order->id, 'current_orders'])));
        $data = [
            'id' => $order->id,
            'type' => 'orders',
            'url' => $dashboardUrl,
            'description_en' => 'New Order',
            'description_ar' => 'طلب جديد ',
        ];
        event(new ActivityLog($data));
    }

    private function checkAddonsValidation($productId, $qty)
    {
        foreach ($qty as $key => $value) {
            $requestQty = intval($value['qty']);
            $addonObject = ProductCustomAddon::with('addon')
                ->whereHas('addon', function ($query) {
                    $query->active();
                })
                ->where('product_id', $productId)
                ->where('custom_addon_id', $value['addon_id'])
                ->first();

            if (is_null($addonObject)) {
                return __('Addon is not found currently!');
            }

            if (!is_null($addonObject->qty) && $requestQty > $addonObject->qty) {
                return __('The required quantity is greater than the current quantity of the addition!');
            }
        }

        return null;
    }

    public function payOrder(Request $request, $id)
    {

        $userId = auth()->id();
        $order = $this->order->findById($id);
        if (!$order) {
            return redirect()->back()->with([
                'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.order_not_found'),
            ])->withInput();
        }

        if ($order->payment_status_id == 3) {
            $order->transactions()->delete();
        }

        if (in_array($request->payment_type, ['knet', 'cc'])) {
            $paymentUrl = $this->payment->send($order, $request->payment_type, 'pay-order');
            if (is_null($paymentUrl)) {
                return $this->redirectToFailedPayment($order->id);
            } else {
                $order->update([
                    'payment_status_id' => 1, // pending
                ]);

                $order->transactions()->create([
                    'method' => $request->payment_type,
                    'result' => null,
                ]);

                return redirect()->away($paymentUrl);
            }
        } elseif ($request->payment_type == 'cash') {
            $order->update([
                'payment_status_id' => 4, // cash
            ]);
            $order->transactions()->create([
                'method' => 'cash',
                'result' => null,
            ]);
            return $this->redirectToPaymentOrOrderPage($order);
        } elseif ($request->payment_type == 'subscriptions_balance') {

            $checkSubscriptionBalanceCondition = $this->checkSubscriptionBalanceCondition($userId);
            if ($checkSubscriptionBalanceCondition == true && floatval(auth()->user()->subscriptions_balance) >= floatval($order->total)) {

                DB::beginTransaction();
                try {
                    $order->update([
                        'payment_status_id' => 5, // subscriptions_balance
                        'payment_confirmed_at' => date('Y-m-d H:i:s'),
                    ]);
                    $order->transactions()->create([
                        'method' => 'subscriptions_balance',
                        'result' => null,
                    ]);
                    $this->decrementUserSubscriptionsBalance($userId, $order->total);
                    // add user subscriptions balance logs
                    $amountBefore = floatval(auth()->user()->subscriptions_balance);
                    $amountAfter = $amountBefore - floatval($order->total);
                    SubscriptionBalanceLog::create([
                        'user_id' => $userId,
                        'order_id' => $order->id,
                        'amount_before' => $amountBefore,
                        'amount' => $order->total,
                        'amount_after' => $amountAfter,
                    ]);

                    $this->sendNotificationToDrivers($order);

                    DB::commit();
                    return view('core::frontend.components._success_order_redirect', ['successMessage' => __('The payment completed successfully!')]);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            } else {
                return redirect()->back()->with([
                    'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.subscriptions_balance_insufficient'),
                ])->withInput();
            }
        } elseif ($request->payment_type == 'loyalty_points') {
            $userLoyaltyPoints = auth()->user()->loyalty_points_count;
            $useDinarCount = calculateUserFilsFromPointsCount($userLoyaltyPoints) / 1000;
            if (floatval($order->total) <= $useDinarCount) {
                $order->update([
                    'payment_status_id' => 6, // loyalty_points
                    'payment_confirmed_at' => date('Y-m-d H:i:s'),
                ]);
                $order->transactions()->create([
                    'method' => 'loyalty_points',
                    'result' => null,
                ]);

                $remainingUserPointsCount = calculateUserPointsCount(floatval($order->total));
                $userPointsCount = $userLoyaltyPoints - $remainingUserPointsCount;
                $order->user->decrement('loyalty_points_count', $userPointsCount);

                LoyaltyPointBalanceLog::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'points_count_before' => $userLoyaltyPoints,
                    'points_count' => $userPointsCount,
                    'points_count_after' => $remainingUserPointsCount,
                ]);

                $this->sendNotificationToDrivers($order);

                return $this->redirectToPaymentOrOrderPage($order);
            } else {
                return redirect()->back()->with([
                    'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.insufficient_loyalty_points_balance'),
                ])->withInput();
            }
        }

        if (in_array($request->payment_type, ['knet', 'cc', 'subscriptions_balance'])) {
            $userPointsCount = calculateUserPointsCount($order->total);
            $order->user->increment('loyalty_points_count', $userPointsCount);
        }

        return redirect()->back()->with([
            'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.payment_not_supported_now'),
        ])->withInput();

    }

    public function payOrderWebhooks(Request $request)
    {
        $this->order->updatePayOrder($request);
    }

    public function payOrderSuccess(Request $request)
    {
        $checkOrder = $this->order->updatePayOrder($request);
        $order = null;
        if ($checkOrder == true) {
            $order = $this->order->findById($request['OrderID']);
        }
        return $checkOrder ? $this->redirectToPaymentOrOrderPage($order) : $this->redirectToFailedPayment($request['OrderID']);
    }

    public function payOrderFailed(Request $request)
    {
        $this->order->updatePayOrder($request);
        return $this->redirectToFailedPayment($request['OrderID']);
    }

    public function redirectToPaymentOrOrderPage($order)
    {
        $this->sendNotificationToDrivers($order);

        return redirect()->route('frontend.orders.show', $order->id)->with([
            'alert' => 'success', 'status' => __('order::frontend.orders.index.alerts.order_success'),
        ]);
    }

}
