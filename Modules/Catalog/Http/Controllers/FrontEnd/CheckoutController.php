<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Repositories\FrontEnd\OrderRepository as Order;
use Modules\Order\Traits\OrderTrait;

class CheckoutController extends Controller
{
    use OrderTrait;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function index(Request $request, $id)
    {
        $order = $this->order->findUserOrderById($id, ['orderCoupons', 'orderCustomAddons', 'orderProducts.orderProductCustomAddons', 'orderProducts.product']);
        if (!$order) {
            return redirect()->back()->with(['status' => __('catalog::frontend.checkout.index.order_not_found'),'alert' => 'danger']);
        }elseif(!in_array($order->orderStatus->flag,['is_ready','on_the_way','delivered'])){
            return redirect()->back()->with(['status' =>__('catalog::frontend.checkout.index.cant_pay'),'alert' => 'danger']);
        }

        $checkSubscriptionBalanceCondition = $this->checkSubscriptionBalanceCondition(auth()->id());
        return view('catalog::frontend.checkout.index', compact('order', 'checkSubscriptionBalanceCondition'));
    }

}
