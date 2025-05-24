<?php

namespace Modules\Order\Traits;

use Modules\Catalog\Entities\Product;

trait OrderCalculationTrait
{
    public function calculateTheOrder($userToken = null)
    {
        $total = $this->totalOrder($userToken);
        $order = $this->orderProducts($userToken);
        $order['subtotal'] = $this->subTotalOrder($userToken);
        $order['shipping'] = $this->getOrderShipping($userToken);
        $order['total'] = $total;
        return $order;
    }

    public function totalOrder($userToken = null)
    {
        return getCartTotal($userToken);
    }

    public function subTotalOrder($userToken = null)
    {
        return getCartSubTotal($userToken);
    }

    public function getOrderShipping($userToken = null)
    {
        return getOrderShipping($userToken);
    }

    public function orderProducts($userToken = null)
    {
        $data = [];
        $subtotal = 0.000;
        $off = 0.000;
        $price = 0.000;
        $profite = 0.000;
        $profitePrice = 0.000;

        foreach (getCartContent($userToken) as $k => $value) {
            $productObject = Product::active()->find($value->attributes->product->id);
            $product['product_id'] = $productObject->id;
            $product['product'] = $value->attributes->product;
            $product['original_price'] = $value->price;
            $product['starch'] = $value->starch ?? ($value->attributes->starch ?? null) ;

            $product['quantity'] = $value->quantity;
            $product['sale_price'] = $value->price;

            $product['off'] = $product['original_price'] - $product['sale_price'];
            $product['original_total'] = $product['original_price'] * $product['quantity'];
            $product['total'] = $product['sale_price'] * $product['quantity'];
            $product['cost_price'] = $value->price;
            $product['total_cost_price'] = $product['cost_price'] * $product['quantity'];
            $product['total_profit'] = $product['total'] - $product['total_cost_price'];
            $product['qty_details'] = $value->attributes->qty_details;

            $off += $product['off'];
            $price += $product['total'];
            $subtotal += $product['original_total'];
            $profitePrice += $product['total_cost_price'];
            $profite += $product['total_profit'];

            $data[] = $product;
        }

        return [
            'profit' => $profite,
            'off' => $off,
            'original_subtotal' => $subtotal,
            'products' => $data,
        ];
    }
}
