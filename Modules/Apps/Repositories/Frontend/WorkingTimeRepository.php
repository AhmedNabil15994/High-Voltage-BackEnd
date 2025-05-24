<?php

namespace Modules\Apps\Repositories\Frontend;

use Carbon\Carbon;
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

    public function getActivePickupWorkingDays()
    {
        return $this->pickupWorkDay->active()->with(['pickupWorkingTimes'=>function($with){
            $with->orderBy('from','asc');
        }])->where('is_full_day', 0)->get();
    }

    public function getActiveDeliveryWorkingDays()
    {
        return $this->deliveryWorkDay->active()->with(['deliveryWorkingTimes'=>function($with){
            $with->orderBy('from','asc');
        }])->where('is_full_day', 0)->get();
    }
}
