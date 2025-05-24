<?php

namespace Modules\Baqat\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class BaqatSubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'baqa' => $this->baqa->title ?? '---',
            'user' => $this->user->name ?? '---',
            'price' => $this->price,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
