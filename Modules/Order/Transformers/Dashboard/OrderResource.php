<?php

namespace Modules\Order\Transformers\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'unread' => $this->unread,
            'total' => $this->total,
            'shipping' => $this->shipping,
            'subtotal' => $this->subtotal,
            'state' => optional(optional(optional($this->orderAddress)->state))->title,
            'order_status_id' => optional($this->orderStatus)->id,
            'order_status_title' => optional($this->orderStatus)->title,
            'deleted_at' => $this->deleted_at,
            'created_at' => date('d-m-Y', strtotime($this->created_at)),
        ];

        if (!is_null($this->paymentStatus)) {
            $type = $this->payment_status_id == 4 ? $this->paymentStatus->flag : optional($this->transactions)->method ?? ($this->paymentStatus->flag == 'failed' ? null : $this->paymentStatus->flag);
            if (!is_null($type)) {
                $response['transaction'] = __('apps::dashboard.datatable.form.payment_types.' . $type);
            } else {
                $response['transaction'] = '---';
            }
        } else {
            $response['transaction'] = '---';
        }

        return $response;
    }
}
