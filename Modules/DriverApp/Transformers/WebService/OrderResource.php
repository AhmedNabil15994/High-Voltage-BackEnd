<?php

namespace Modules\DriverApp\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Entities\Product;
use Modules\User\Transformers\WebService\UserResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $isPaid = checkPaidStatus($this->resource);
        $result = [
            'id' => $this->id,
            'order_type' => $this->order_type,
            'is_fast_delivery' => $this->is_fast_delivery,
            'total' => number_format($this->total, 3),
            'shipping' => number_format($this->shipping, 3),
            'subtotal' => number_format($this->subtotal, 3),
            'transaction' => optional($this->transactions)->method,
            'order_status' => [
                'title' => optional($this->orderStatus)->title,
                'image' => optional($this->orderStatus)->image ? url($this->orderStatus->image) : url(config('setting.images.logo')),
                'flag' => optional($this->orderStatus)->flag,
                'is_success' => optional($this->orderStatus)->is_success,
                'sort' => optional($this->orderStatus)->sort,
            ],
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'order_notes' => $this->order_notes,
            'notes_from_client' => $this->notes,
            'is_paid' => $isPaid,
            'payment_status' => $isPaid ? __('Paid') : __('UnPaid'),
            'payment_type' => getPaymentType($this->resource),
        ];

        if ($this->orderTimes && $this->orderTimes->receiving_data) {
            $customTime = $this->extractTimes($this->orderTimes->receiving_data['receiving_time']);
            $result['pickup_date'] = [
                'date'      => date('Y-m-d',strtotime($this->orderTimes->receiving_data['receiving_date'])),
                'time_from' => date('A h:i',strtotime($customTime['from'])),
                'time_to'   => date('A h:i',strtotime($customTime['to'])),
            ];
        }

        if ($this->orderTimes && $this->orderTimes->delivery_data) {
            $customTime = $this->extractTimes($this->orderTimes->delivery_data['delivery_time']);
            $result['delivery_date'] = [
                'date'      => date('Y-m-d',strtotime($this->orderTimes->delivery_data['delivery_date'])),
                'time_from' => date('A h:i',strtotime($customTime['from'])),
                'time_to'   => date('A h:i',strtotime($customTime['to'])),
            ];
        }

        if (is_null($this->unknownOrderAddress)) {
            $result['address'] = new OrderAddressResource($this->orderAddress);
        } else {
            $result['address'] = new UnknownOrderAddressResource($this->unknownOrderAddress);
        }

        if (!is_null($this->driver)) {
            $result['driver'] = new OrderDriverResource($this->driver);
        } else {
            $result['driver'] = null;
        }

        if (!is_null($this->user)) {
            $result['user'] = new UserResource($this->user);
        } else {
            $result['user'] = null;
        }

        $orderProducts = $this->orderCustomAddons->groupBy('orderProduct.product_id');
        $allProducts = [];
        foreach ($orderProducts as $key => $value) {
            $productModel = Product::find($key);
            $customProductObject = [
                'id' => $productModel->id,
                'title' => $productModel->title,
                'image' => $productModel->image ? url($productModel->image) : null,
                'addons' => OrderProductResource::collection($value),
            ];
            $allProducts[] = $customProductObject;
        }
        $result['products'] = $allProducts;

        return $result;
    }

    private function extractTimes($time)
    {
        $result = explode('-', $time);
        return [
            'from' => $result[0],
            'to' => $result[1],
        ];
    }
}
