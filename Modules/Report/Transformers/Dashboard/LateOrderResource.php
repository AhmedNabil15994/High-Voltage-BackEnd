<?php

namespace Modules\Report\Transformers\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class LateOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $deliveryTime = $this->buildFullOrderTime($this->orderTimes->delivery_data, 'delivery');
        $driverReceivingTime = date('d-m-Y H:i', strtotime($this->driverOrderStatuses->first()->created_at));

        $d = explode(' - ', $deliveryTime);
        $date = $d[0]??'1970-01-01';
        $time = $d[1]??'00:00:00';
        if($time == '00:00:00'){

            $response = [
                "id" => $this->id,
                'driver_id' => optional(optional($this->driver)->driver)->id ?? null,
                'driver_name' => optional(optional($this->driver)->driver)->name ?? '---',
                'delivery_time' => $deliveryTime,
                'driver_receiving_time' => $driverReceivingTime,
                'delay_time' => '',//$deliveryTime1->diffInHours($driverReceivingTime) . ' ' . __('Hour'),
                'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            ];
        }else{
            $deliveryTime1 = Carbon::createFromFormat('Y-m-d H:i a', $d[0] . ' ' . $d[1]);

            $response = [
                "id" => $this->id,
                'driver_id' => optional(optional($this->driver)->driver)->id ?? null,
                'driver_name' => optional(optional($this->driver)->driver)->name ?? '---',
                'delivery_time' => $deliveryTime,
                'driver_receiving_time' => $driverReceivingTime,
                'delay_time' => $deliveryTime1->diffInHours($driverReceivingTime) . ' ' . __('Hour'),
                'created_at' => date('d-m-Y H:i', strtotime($this->created_at)),
            ];
        }

        return $response;
    }

    private function buildFullOrderTime($data, $prefix)
    {
        $time = $data[$prefix . '_date'];
        $time .= ' ';
        $time .= $data[$prefix . '_time'];
        $time .= ' ';
        $time .= $data[$prefix . '_time_format_type'] ?? '';
        return $time;
    }
}
