<?php

namespace Modules\Baqat\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class BaqatResource extends JsonResource
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
            'duration_by_days' => $this->duration_by_days,
            'price' => $this->offer ? $this->offer->price : $this->price,
            'client_price' => $this->client_price,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];

        if ($this->offer) {
            if (!is_null($this->offer->offer_price)) {
                $response['price'] = $this->offer->offer_price;
            } else {
                $response['price'] = number_format(calculateOfferAmountByPercentage($this->price, $this->offer->percentage), 3);
            }
        } else {
            $response['price'] = $this->price;
        }

        return $response;
    }
}
