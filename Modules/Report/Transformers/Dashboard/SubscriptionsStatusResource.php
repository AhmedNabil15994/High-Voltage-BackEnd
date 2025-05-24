<?php

namespace Modules\Report\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionsStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $expiredFlag = ($this->new_end_at == null && $this->end_at < date('Y-m-d')) ||
                        ($this->new_end_at != null && $this->new_end_at < date('Y-m-d')) ? 0 : 1;
        return [
            'id' => $this->id,
            'baqa' => $this->baqa->title ?? '---',
            'user' => $this->user->name ?? '---',
            // 'price' => $this->price,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' =>  __('report::dashboard.reports.index.form.subscriptions_status.datatable.status_'. $expiredFlag),
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}
