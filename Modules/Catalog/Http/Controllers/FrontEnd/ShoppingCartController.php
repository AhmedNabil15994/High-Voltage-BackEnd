<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Entities\ProductCustomAddon;
use Modules\Catalog\Http\Requests\FrontEnd\CartRequest;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\User\Repositories\FrontEnd\AddressRepository as AddressRepo;

class ShoppingCartController extends Controller
{
    use ShoppingCartTrait;

    protected $product;
    protected $address;

    public function __construct(Product $product, AddressRepo $address)
    {
        $this->product = $product;
        $this->address = $address;
    }

    public function index()
    {
        $items = getCartContent(null, true);
        return view('catalog::frontend.shopping-cart.index', compact('items'));
    }

    public function getOrderSummary(Request $request)
    {
        $userToken = $this->getCartUserToken();
        $items = getCartContent();
        if ($items->count() == 0) {
            return redirect()->back()->withErrors(__('Choose items firstly!'));
        }
        $orderInfo = json_decode(get_cookie_value('DIRECT_ORDER_COOKIE_' . $userToken), true);
        if (is_null($orderInfo)) {
            return redirect()->route('frontend.order_request.start');
        }
        $orderAddress = $this->address->findById($orderInfo['address_id']);
        return view('catalog::frontend.shopping-cart.order-summary', compact('items', 'orderInfo', 'orderAddress'));
    }

    public function totalCart()
    {
        return getCartSubTotal();
    }

    public function headerCart()
    {
        return view('apps::frontend.layouts._cart');
    }

    public function createOrUpdate(CartRequest $request, $id)
    {
        $data = [];
        $userToken = $this->getCartUserToken();
        $directOrderCookie = json_decode(get_cookie_value('DIRECT_ORDER_COOKIE_' . $userToken), true) ?? [];
        $isFastDelivery = isset($directOrderCookie['is_fast_delivery']) && $directOrderCookie['is_fast_delivery'] == 1;
        $product = $this->product->findById($id);
        if (!$product) {
            return response()->json(["errors" => __('cart::api.cart.product.not_found')], 422);
        }

        $product->product_type = 'product';
        $data['productDetailsRoute'] = route('frontend.products.index', [$product->slug]);
        $data['productTitle'] = $product->title;
        $productCartId = $product->id;

        $checkProduct = is_null(getCartItemById($productCartId));

        $allQty = $this->getTotalOfQtyAndPrices($id, $request->qty, $isFastDelivery);
        if (gettype($allQty) == 'string') {
            return response()->json(["errors" => $allQty], 422);
        }
        if (!isset($allQty['qty_details'])) {
            $cartItem = Cart::session($userToken)->remove($productCartId);
            $data["total"] = number_format(getCartTotal(), 3);
            $data["subTotal"] = number_format(getCartSubTotal(), 3);
            $data["cartCount"] = count(getCartContent(null, true));
            return response()->json(["message" => __('catalog::frontend.cart.deleted_successfully'), "data" => $data], 200);
        }
        $request->request->add(['all_qty' => $allQty]);
        $request->request->remove('qty');
        $errors = $this->addOrUpdateCart($product, $request);
        if ($errors) {
            return response()->json(["errors" => $errors], 422);
        }

        $data["total"] = number_format(getCartTotal(), 3);
        $data["subTotal"] = number_format(getCartSubTotal(), 3);
        $data["cartCount"] = count(getCartContent(null, true));

        if ($checkProduct) {
            return response()->json(["message" => __('catalog::frontend.cart.add_successfully'), "data" => $data], 200);
        } else {
            return response()->json(["message" => __('catalog::frontend.cart.updated_successfully'), "data" => $data], 200);
        }
    }

    public function delete(Request $request, $id)
    {
        if ($request->product_type == 'product') {
            $deleted = $this->deleteProductFromCart($id);
        } else {
            $deleted = $this->deleteProductFromCart('var-' . $id);
        }

        if ($deleted) {
            $couponDiscount = $this->getConditionByName('coupon_discount');
            if (!is_null($couponDiscount)) {
                $couponCode = $couponDiscount->getAttributes()['coupon']->code ?? null;
                $this->applyCouponOnCart($couponCode);
            }
            return redirect()->back()->with(['alert' => 'success', 'status' => __('catalog::frontend.cart.delete_item')]);
        }

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.cart.error_in_cart')]);
    }

    public function deleteByAjax(Request $request)
    {
        if ($request->product_type == 'product') {
            $deleted = $this->deleteProductFromCart($request->id);
        } else {
            $deleted = $this->deleteProductFromCart('var-' . $request->id);
        }

        if ($deleted) {
            $couponDiscount = $this->getConditionByName('coupon_discount');
            if (!is_null($couponDiscount)) {
                $couponCode = $couponDiscount->getAttributes()['coupon']->code ?? null;
                $this->applyCouponOnCart($couponCode);
            }
            $result["cartCount"] = count(getCartContent(null, true));
            $result["cartTotal"] = number_format(getCartSubTotal(), 3);
            return response()->json(["message" => __('catalog::frontend.cart.delete_item'), "result" => $result], 200);
        }

        return response()->json(["errors" => __('catalog::frontend.cart.error_in_cart')], 422);
    }

    public function clear(Request $request)
    {
        $cleared = $this->clearCart();

        if ($cleared) {
            return redirect()->back()->with(['alert' => 'success', 'status' => __('catalog::frontend.cart.clear_cart')]);
        }

        return redirect()->back()->with(['alert' => 'danger', 'status' => __('catalog::frontend.cart.error_in_cart')]);
    }

    private function getTotalOfQtyAndPrices($productId, $qty, $isFastDelivery)
    {
        $result = [];
        $totalQty = 0;
        $totalPrice = 0;
        foreach ($qty as $key => $value) {
            $requestQty = intval($value['qty']);

            if ($requestQty > 0) {
                $totalQty += $requestQty;
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

                $addonPrice = floatval($addonObject->price);
                if ($isFastDelivery == true) {
                    $addonPrice = 2 * $addonPrice;
                }

                $totalPrice += intval($value['qty']) * $addonPrice;
                $result['models'][$key] = $addonObject;
                $result['qty_details'][$key] = [
                    'qty' => $requestQty,
                    'price' => $addonPrice,
                    'addon_id' => $value['addon_id'],
                ];
            }
        }
        $result['total_qty'] = $totalQty;
        $result['total_price'] = $totalPrice;
        return $result;
    }

}
