<?php

namespace Modules\Area\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'city_id' => optional($this->city)->title,
            'deleted_at' => $this->deleted_at,
            'orders_count' => $this->orders_count ?? 0,
            'orders_total' => $this->orders_sum_total ?? 0,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
        $ordersCount = $this->orders_count;
        $ordersSum = $this->orders_sum_total;
        $ordersAverage = $ordersCount > 0 ? ($ordersSum / $ordersCount) : 0;
        $response['orders_average'] = number_format($ordersAverage, 3);
        return $response;
    }
}
