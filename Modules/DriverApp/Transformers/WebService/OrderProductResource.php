<?php

namespace Modules\DriverApp\Transformers\WebService;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request)
    {
        $result = [
            'selling_price' => $this->price,
            'qty' => $this->qty,
            'total' => $this->total,
        ];

        $result['addon'] = [
            'id' => optional($this->addon)->id,
            'title' => optional($this->addon)->title,
            'image' => optional($this->addon)->image ? url(optional($this->addon)->image) : null,
        ];

        return $result;
    }
}
