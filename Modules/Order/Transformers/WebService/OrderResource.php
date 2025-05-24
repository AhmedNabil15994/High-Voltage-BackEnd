<?php

namespace Modules\Order\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        $allOrderProducts = $this->orderProducts->mergeRecursive($this->orderVariations);
        $result = [
            'id' => $this->id,
            'total' => number_format($this->total, 3),
            'shipping' => number_format($this->shipping, 3),
            'subtotal' => number_format($this->subtotal, 3),
            'transaction' => optional($this->transactions)->method,
            'order_status' => [
                'flag' => optional($this->orderStatus)->flag,
                'title' => optional($this->orderStatus)->title,
            ],
            'is_rated' => $this->checkUserRateOrder($this->id),
            'rate' => $this->getOrderRate($this->id),
            'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            'notes' => $this->notes,
            'products' => OrderProductResource::collection($allOrderProducts),
        ];

        $result['address'] = new OrderAddressResource($this->orderAddress);

        return $result;
    }
}
