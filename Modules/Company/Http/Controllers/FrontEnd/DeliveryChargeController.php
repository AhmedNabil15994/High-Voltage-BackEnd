<?php

namespace Modules\Company\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Apps\Repositories\Frontend\WorkingTimeRepository as WorkingTimeRepo;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Company\Repositories\FrontEnd\DeliveryChargeRepository as DeliveryCharge;
use Modules\Order\Entities\OrderDriver;
use Modules\Order\Traits\OrderTrait;
use Modules\User\Entities\User;

class DeliveryChargeController extends Controller
{
    use ShoppingCartTrait;
    use OrderTrait;

    protected $deliveryCharge;
    protected $workingTime;

    public function __construct(DeliveryCharge $deliveryCharge, WorkingTimeRepo $workingTime)
    {
        $this->deliveryCharge = $deliveryCharge;
        $this->workingTime = $workingTime;
    }

    public function getDeliveryInfo(Request $request)
    {
        $deliveryCharge = $this->deliveryCharge->findByStateId($request->state_id);
        $workingTimes = $this->buildWorkingTimes($request, $this->workingTime->getActivePickupWorkingDays(), $this->workingTime->getActiveDeliveryWorkingDays());
        $pickupWorkingDays = $this->removePreviousTimes($workingTimes['pickupWorkingDays'], 'pickup_working_times');

        $newPickUp = $this->checkDriverTimes($request->state_id,$pickupWorkingDays,'pickup_working_times','pick_up_time_id');
        $defaultPickupDayTime = $this->buildDefaultTime($newPickUp, 'pickup_working_times', ['receiving_time', 'receiving_time_text']);
        $selectedPickupFirstDay = $defaultPickupDayTime['default_day'];

        $selectedPickupFirstTime['receiving_time'] = $defaultPickupDayTime['default_time']['receiving_time'];
        $selectedPickupFirstTime['receiving_time_text'] = $defaultPickupDayTime['default_time']['receiving_time_text'];
        $selectedPickupFirstTime['pickup_working_times_id'] = $defaultPickupDayTime['time_id'];

        return response()->json([
            "message" => null,
            "data" => ['deliveryCharge' => $deliveryCharge],
            'vData' =>  view('order::frontend.orders.partial._receiving_days', compact('newPickUp'))->render(),
            'mData' => $selectedPickupFirstTime,
        ], 200);
    }
}
