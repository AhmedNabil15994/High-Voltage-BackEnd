<?php

namespace Modules\Coupon\Http\Controllers\FrontEnd;

use Carbon\Carbon;
use Cart;
use Darryldecode\Cart\CartCondition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Repositories\FrontEnd\CouponRepository;
use Modules\Order\Entities\OrderCoupon;
use Modules\Order\Repositories\FrontEnd\OrderRepository as OrderRepo;

class CouponController extends Controller
{
    use ShoppingCartTrait;

    protected $coupon;
    protected $order;

    public function __construct(CouponRepository $coupon, OrderRepo $order)
    {
        $this->coupon = $coupon;
        $this->order = $order;
    }

    ### Start - Check Frontend Coupon
    public function checkCoupon(Request $request)
    {
        if (auth()->check()) {
            $userToken = auth()->user()->id ?? null;
        } else {
            $userToken = get_cookie_value(config('core.config.constants.CART_KEY')) ?? null;
        }

        if (is_null($userToken)) {
            return response()->json(["errors" => __('apps::frontend.general.user_token_not_found')], 422);
        }

        $coupon = Coupon::where('code', $request->code)->active()->first();

        // Check if coupon is used before by this user
        /*$couponCondition = getCartConditionByName($userToken, 'coupon_discount');
        if (!is_null($couponCondition))
        return response()->json(["errors" => __('coupon::frontend.coupons.validation.coupon_is_used')], 422);*/

        if ($coupon) {

            if (!is_null($coupon->start_at) && !is_null($coupon->expired_at)) {
                if ($coupon->start_at > Carbon::now()->format('Y-m-d') || $coupon->expired_at < Carbon::now()->format('Y-m-d')) {
                    return response()->json(["errors" => __('coupon::frontend.coupons.validation.code.expired')], 422);
                }
            }

            if (auth()->guest() && !in_array('guest', $coupon->user_type ?? [])) {
                return response()->json(["errors" => __('coupon::frontend.coupons.validation.code.custom')], 422);
            }

            if (auth()->check() && !in_array('user', $coupon->user_type ?? [])) {
                return response()->json(["errors" => __('coupon::frontend.coupons.validation.code.custom')], 422);
            }

            $coupon_users = $coupon->users->pluck('id')->toArray() ?? [];
            if ($coupon_users != []) {
                if (auth()->check() && !in_array(auth()->id(), $coupon_users)) {
                    return response()->json(["errors" => __('coupon::frontend.coupons.validation.code.custom')], 422);
                }

            }

            if (auth()->check()) {
                $userCouponsCount = OrderCoupon::where('coupon_id', $coupon->id)
                    ->whereHas('order', function ($q) {
                        $q->where('user_id', auth()->id());
                        $q->whereHas('paymentStatus', function ($q) {
                            $q->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
                        });
                    })->count();

                if (!is_null($coupon->user_max_uses) && $userCouponsCount > intval($coupon->user_max_uses)) {
                    return response()->json(["errors" => __('coupon::frontend.coupons.validation.user_max_uses')], 422);
                }
            }

            // Remove Old General Coupon Condition
            $this->removeCartConditionByType('coupon_discount', $userToken);

            $cartItems = getCartContent($userToken);
            $prdList = $this->getProductsList($coupon, $coupon->flag);
            $prdListIds = array_values(!empty($prdList) ? array_column($prdList->toArray(), 'id') : []);
            $conditionValue = $this->addProductCouponCondition($cartItems, $coupon, $userToken, $prdListIds);
            $data = [
                "coupon_value" => $conditionValue > 0 ? number_format($conditionValue, 3) : 0,
                "sub_total" => number_format(getCartSubTotal(), 3),
                "total" => number_format(getCartTotal(), 3),
                "delivery_price" => !is_null(getOrderShipping()) ? number_format(getOrderShipping(), 3) : null,
            ];

            /*if (!is_null($coupon->flag)) {
            $prdList = $this->getProductsList($coupon, $coupon->flag);
            $prdListIds = array_values(!empty($prdList) ? array_column($prdList->toArray(), 'id') : []);
            $conditionValue = $this->addProductCouponCondition($cartItems, $coupon, $userToken, $prdListIds);
            $data = [
            "coupon_value" => $conditionValue > 0 ? number_format($conditionValue, 3) : 0,
            "sub_total" => number_format(getCartSubTotal(), 3),
            "total" => number_format(getCartTotal(), 3),
            ];
            } else {
            $discount_value = 0;
            if ($coupon->discount_type == "value")
            $discount_value = $coupon->discount_value;
            elseif ($coupon->discount_type == "percentage")
            $discount_value = (getCartSubTotal($userToken) * $coupon->discount_percentage) / 100;

            $this->addProductCouponCondition($cartItems, $coupon, $userToken, []);
            // Apply Coupon Discount Condition On All Products In Cart
            $resultCheck = $this->discountCouponCondition($coupon, $discount_value, $userToken);
            if (!$resultCheck)
            return response()->json(["errors" => __('coupon::frontend.coupons.validation.condition_error')], 422);

            $data = [
            "coupon_value" => number_format($discount_value, 3),
            "sub_total" => number_format(getCartSubTotal(), 3),
            "total" => number_format(getCartTotal(), 3),
            ];
            }*/

            return response()->json(["message" => __('coupon::frontend.coupons.checked_successfully'), "data" => $data], 200);
        } else {
            return response()->json(["errors" => __('coupon::frontend.coupons.validation.code.not_found')], 422);
        }
    }

    protected function getProductsList($coupon, $flag = 'products')
    {
        $coupon_vendors = $coupon->vendors ? $coupon->vendors->pluck('id')->toArray() : [];
        $coupon_products = $coupon->products ? $coupon->products->pluck('id')->toArray() : [];
        $coupon_categories = $coupon->categories ? $coupon->categories->pluck('id')->toArray() : [];

        $products = Product::where('status', true);

        if ($flag == 'products') {
            $products = $products->whereIn('id', $coupon_products);
        }

        if ($flag == 'vendors') {
            $products = $products->whereHas('vendor', function ($query) use ($coupon_vendors, $flag) {
                $query->whereIn('id', $coupon_vendors);
                $query->active();
                $query->whereHas('subbscription', function ($q) {
                    $q->active()->unexpired()->started();
                });
            });
        }

        if ($flag == 'categories') {
            $products = $products->whereHas('categories', function ($query) use ($coupon_categories) {
                $query->active();
                $query->whereIn('product_categories.category_id', $coupon_categories);
            });
        }

        return $products->get(['id']);
    }

    private function addProductCouponCondition($cartItems, $coupon, $userToken, $prdListIds = [])
    {
        $totalValue = 0;
        $discount_value = 0;

        foreach ($cartItems as $cartItem) {

            if ($cartItem->attributes->product->product_type == 'product') {
                $prdId = $cartKey = $cartItem->id;
            } else {
                $prdId = $cartItem->attributes->product->product->id;
                $cartKey = $cartItem->id;
            }
            // Remove Old Condition On Product
            Cart::session($userToken)->removeItemCondition($cartKey, 'product_coupon');

            if (count($prdListIds) > 0 && in_array($prdId, $prdListIds)) {

                if ($coupon->discount_type == "value") {
                    $discount_value = $coupon->discount_value;
                    $totalValue += intval($cartItem->quantity) * $discount_value;
                } elseif ($coupon->discount_type == "percentage") {
                    $discount_value = (floatval($cartItem->price) * $coupon->discount_percentage) / 100;
                    $totalValue += $discount_value * intval($cartItem->quantity);
                }
                $prdCoupon = new CartCondition(array(
                    'name' => 'product_coupon',
                    'type' => 'product_coupon',
                    'value' => number_format($discount_value * -1, 3),
                ));
                addItemCondition($cartKey, $prdCoupon, $userToken);
                $this->saveEmptyDiscountCouponCondition($coupon, $userToken); // to use it to check coupon in order
            }
        }

        // check free delivery in coupon
        if ($coupon->free_delivery == 1) {
            $deliveryCondition = $this->getConditionByName('company_delivery_fees');
            if (!is_null($deliveryCondition)) {
                $this->addFreeDeliveryChargeCondition($userToken, $deliveryCondition);
            }
        }

        return $totalValue;
    }
    ### End - Check Frontend Coupon

    public function index(Request $request)
    {
        $coupons = $this->coupon->getActiveCoupons($request);
        return view('coupon::frontend.index', compact('coupons'));
    }

    public function applyCouponOnOrder(Request $request, $orderId)
    {
        $userToken = auth()->id();
        $order = $this->order->findUserOrderById($orderId, ['orderCoupons', 'orderCustomAddons']);
        if (!$order) {
            return response()->json(["status" => false, 'errors' => __('order::frontend.orders.index.alerts.order_not_found')], 422);
        }

        $coupon = Coupon::where('code', $request->code)->active()->first();
        if ($coupon) {

            if (!is_null($coupon->start_at) && !is_null($coupon->expired_at)) {
                if ($coupon->start_at > Carbon::now()->format('Y-m-d') || $coupon->expired_at < Carbon::now()->format('Y-m-d')) {
                    return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.code.expired')], 422);
                }
            }

            if (auth()->guest() && !in_array('guest', $coupon->user_type ?? [])) {
                return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.code.custom')], 422);
            }

            if (auth()->check() && !in_array('user', $coupon->user_type ?? [])) {
                return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.code.custom')], 422);
            }

            $coupon_users = $coupon->users->pluck('id')->toArray() ?? [];
            if ($coupon_users != []) {
                if (auth()->check() && !in_array(auth()->id(), $coupon_users)) {
                    return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.code.custom')], 422);
                }
            }

            $coupon_states = $coupon->states->pluck('id')->toArray() ?? [];
            if ($coupon_states != []) {
                if (!in_array(optional($order->orderAddress)->state_id, $coupon_states)) {
                    return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.code.not_customize_to_area')], 422);
                }
            }

            if (auth()->check()) {
                $userCouponsCount = OrderCoupon::where('coupon_id', $coupon->id)
                    ->whereHas('order', function ($q) {
                        $q->where('user_id', auth()->id());
                        $q->whereHas('paymentStatus', function ($q) {
                            $q->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
                        });
                    })->count();

                if (!is_null($coupon->user_max_uses) && $userCouponsCount > intval($coupon->user_max_uses)) {
                    return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.user_max_uses')], 422);
                }
            }

            // check users_count
            $usersCount = OrderCoupon::where('coupon_id', $coupon->id)
                ->whereHas('order', function ($q) {
                    $q->distinct('user_id');
                    $q->where('user_id', '!=', auth()->id());
                    $q->whereHas('paymentStatus', function ($q) {
                        $q->whereIn('flag', ['success', 'cash', 'subscriptions_balance', 'loyalty_points']);
                    });
                })->count();

            if (!is_null($coupon->users_count) && $usersCount >= intval($coupon->users_count)) {
                return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.users_count')], 422);
            }

            if ($order->orderCoupons()->count() > 0) {
                return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.order_already_has_coupon')], 422);
            }

            DB::beginTransaction();

            try {
                $prdList = $this->getProductsList($coupon, $coupon->flag);
                $couponProductIds = array_values(!empty($prdList) ? array_column($prdList->toArray(), 'id') : []);
                $totalDiscountResult = $this->calculateOrderCouponTotalValue($coupon, $order->orderCustomAddons, $couponProductIds);

                if (!is_null($coupon->max_discount_percentage_value) && $totalDiscountResult['total'] > floatval($coupon->max_discount_percentage_value)) {
                    return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.max_discount_percentage_value')], 422);
                }

                if ($totalDiscountResult['total'] >= floatval($order->subtotal)) {
                    $newOrderSubTotal = 0;
                    $newOrderTotal = floatval($order->shipping);
                } else {
                    $newOrderSubTotal = floatval($order->subtotal) - $totalDiscountResult['total'];
                    $newOrderTotal = floatval($order->total) - $totalDiscountResult['total'];
                }

                if ($coupon->free_delivery == 1) {
                    $shippingAfterDiscount = 0;
                    $newOrderTotal = $newOrderTotal - floatval($order->shipping);
                } else {
                    $shippingAfterDiscount = floatval($order->shipping);
                }

                $orderData = [
                    'subtotal' => $newOrderSubTotal,
                    'off' => $totalDiscountResult['total'],
                    'shipping' => $shippingAfterDiscount,
                    'total' => $newOrderTotal,
                ];

                if ($newOrderTotal == 0) {
                    $orderData['payment_status_id'] = 2; // success
                    $orderData['payment_confirmed_at'] = date('Y-m-d H:i:s');
                }

                $order->update($orderData);
                $order->orderCoupons()->create([
                    'coupon_id' => $coupon->id,
                    'code' => $coupon->code,
                    'discount_type' => $coupon->discount_type,
                    'discount_percentage' => $coupon->discount_percentage,
                    'discount_value' => $coupon->discount_value,
                    'products' => $totalDiscountResult['products'],
                ]);
                $data = [
                    "original_subtotal" => number_format($order->original_subtotal, 3),
                    "subtotal" => number_format($order->subtotal, 3),
                    "total" => number_format($order->total, 3),
                    "shipping" => number_format($order->shipping, 3),
                    "off" => number_format($totalDiscountResult['total'], 3),
                ];

                DB::commit();
                return response()->json(["status" => true, "message" => __('coupon::frontend.coupons.checked_successfully'), "data" => $data], 200);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            return response()->json(["status" => false, "errors" => __('coupon::frontend.coupons.validation.code.not_found')], 422);
        }
    }

    private function calculateOrderCouponTotalValue($coupon, $orderCustomAddons, $couponProductIds)
    {
        $totalDiscount = 0;
        $intersectedProducts = [];
        foreach ($orderCustomAddons as $key => $orderCustomAddon) {
            if (in_array($orderCustomAddon->orderProduct->product_id, $couponProductIds)) {
                if ($coupon->discount_type == "percentage") {
                    $discount_value = (floatval($orderCustomAddon->price) * $coupon->discount_percentage) / 100;
                    $totalDiscount += $discount_value * intval($orderCustomAddon->qty);
                } else {
                    $totalDiscount = $coupon->discount_value;
                }
                if (!in_array($orderCustomAddon->orderProduct->product_id, $intersectedProducts)) {
                    $intersectedProducts[] = $orderCustomAddon->orderProduct->product_id;
                }
            }
        }

        return [
            'total' => $totalDiscount,
            'products' => $intersectedProducts,
        ];
    }
}
