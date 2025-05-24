<?php

namespace Modules\Catalog\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'value' => $this->title,
        ];

        if ($this->children()->count()) {

            $response['items'] = CategoryTreeResource::collection($this->children)->jsonSerialize();
            $response['opened'] = true;
        }

        return $response;
    }
}
