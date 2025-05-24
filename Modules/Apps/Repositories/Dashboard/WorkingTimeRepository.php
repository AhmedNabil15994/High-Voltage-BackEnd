<?php

namespace Modules\Apps\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Apps\Entities\DeliveryWorkingDay;
use Modules\Apps\Entities\PickupWorkingDay;

class WorkingTimeRepository
{
    protected $pickupWorkDay;
    protected $deliveryWorkDay;

    public function __construct(PickupWorkingDay $pickupWorkDay, DeliveryWorkingDay $deliveryWorkDay)
    {
        $this->pickupWorkDay = $pickupWorkDay;
        $this->deliveryWorkDay = $deliveryWorkDay;
    }

    public function getAllPickupWorkingDays()
    {
        return $this->pickupWorkDay->get();
    }

    public function getAllDeliveryWorkingDays()
    {
        return $this->deliveryWorkDay->get();
    }

    public function getActivePickupWorkingDays()
    {
        return $this->pickupWorkDay->active()->where('is_full_day', 0)->get();
    }

    public function getActiveDeliveryWorkingDays()
    {
        return $this->deliveryWorkDay->active()->where('is_full_day', 0)->get();
    }
    
    public function save($request)
    {
        DB::beginTransaction();

        try {

            $this->saveWorkingTimes($request->selected_days['pickup'], new PickupWorkingDay(), 'pickup');
            $this->saveWorkingTimes($request->selected_days['delivery'], new DeliveryWorkingDay(), 'delivery');

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function saveWorkingTimes($days, $model, $type)
    {
        foreach ($days as $key => $day) {
            $model = $model->where('day_code', $key)->first();
            if (isset($day['status']) && $day['status'] == 'on') {
                $model->update(['status' => 1, 'is_full_day' => $day['is_full_day']]);
                if ($day['is_full_day'] == 0 && !empty($day['times'])) {

                    if ($type == 'pickup') {
                        $model->pickupWorkingTimes()->delete();
                        foreach ($day['times'] as $k => $time) {
                            $model->pickupWorkingTimes()->create([
                                'id' => (string) Str::orderedUuid(),
                                'from' => date("H:i:s", strtotime($time['from'])),
                                'to' => date("H:i:s", strtotime($time['to'])),
                            ]);
                        }
                    } else {
                        $model->deliveryWorkingTimes()->delete();
                        foreach ($day['times'] as $k => $time) {
                            $model->deliveryWorkingTimes()->create([
                                'id' => (string) Str::orderedUuid(),
                                'from' => date("H:i:s",strtotime($time['from'])),
                                'to' => date("H:i:s",strtotime($time['to'])),
                            ]);
                        }
                    }
                }
            } else {
                $model->update(['status' => 0]);
            }
        }
    }
}
