<?php

namespace Modules\Order\Traits;

use Carbon\Carbon;
use Doctrine\DBAL\Driver;
use Modules\Notification\Traits\SendNotificationTrait;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderDriver;
use Modules\User\Entities\Address;
use Modules\User\Entities\DriverState;
use Modules\User\Entities\User;
use Modules\User\Entities\UserFireBaseToken;

trait OrderTrait
{
    use SendNotificationTrait;

    public function decrementUserSubscriptionsBalance($userId, $amount)
    {
        return User::find($userId)->decrement('subscriptions_balance', $amount);
    }

    public function checkSubscriptionBalanceCondition($userId)
    {
        $lastUserSubscription = getUserActiveSubscription($userId);
        if (!is_null($lastUserSubscription)) {
            $startDate = Carbon::createFromFormat('Y-m-d', $lastUserSubscription->start_at);
            $endDate = Carbon::createFromFormat('Y-m-d', $lastUserSubscription->end_at);
            $check = Carbon::now()->between($startDate, $endDate) && auth()->user()->subscriptions_balance > 0;
        } else {
            $check = false;
        }
        return $check;
    }

    public function getDriversWithinOrderState($stateId)
    {
        return DriverState::where('status', 1)->where('state_id', $stateId)->pluck('user_id')->toArray();
    }

    public function assignDriverToOrder($details,$orderId){
        $addressObj = Address::find($details['address_id']);
        $state_id = $addressObj->state_id;

        $pickUpTimeId = $details['pickup_working_times_id'];
        $deliveryTimeId = $details['delivery_working_times_id'];

        $drivers = User::whereHas('driverStates',function ($q) use ($state_id){
            $q->where('state_id',$state_id);
        })->get(['id','maximum_received_orders_count']);
        $driverId = null;

        foreach ($drivers as $driver){
            $driverOrdersCount = OrderDriver::where([
                ['pick_up_time_id',$pickUpTimeId],
                ['delivery_time_id',$deliveryTimeId],
                ['user_id',$driver->id],
                ['accepted',1],
            ])->count();
            if($driverOrdersCount < $driver->maximum_received_orders_count){
                $driverId = $driver->id;
                break;
            }
        }

        if($driverId){
            // Assign Order To Driver
            return OrderDriver::insert([
                'accepted' => 1,
                'delivered' => 0,
                'user_id'   => $driverId,
                'order_id'  => $orderId,
                'pick_up_time_id' => $pickUpTimeId,
                'delivery_time_id'  => $deliveryTimeId,
            ]);
        }
    }

    public function sendNotificationToDrivers($order)
    {
        $tokens = [];
        $orderStateId = $order->orderAddress->state_id ?? null;
        $drivers = $this->getDriversWithinOrderState($orderStateId);
        if (!empty($drivers)) {
            foreach ($drivers as $key => $id) {
                $tokens = UserFireBaseToken::where('user_id', $id)->pluck('firebase_token')->toArray();
            }
            if (count($tokens) > 0) {
                $data = [
                    'title' => __('order::dashboard.orders.notification.new_order'),
                    'body' => __('order::dashboard.orders.notification.body') . ' - ' . optional($order->orderStatus)->title,
                    'type' => 'order',
                    'id' => $order->id,
                ];

                $this->send($data, $tokens, 'driver_app');
            }
        }

        return true;
    }

    public function buildWorkingTimes($request, $activePickupWorkingDays, $activeDeliveryWorkingDays)
    {
        $pickupWorkingDays = $this->sortWorkingTimes($activePickupWorkingDays, 'pickup', $request);
        $deliveryWorkingDays = $this->sortWorkingTimes($activeDeliveryWorkingDays, 'delivery', $request);
        return [
            'pickupWorkingDays' => $pickupWorkingDays,
            'deliveryWorkingDays' => $deliveryWorkingDays,
        ];
    }

    public function sortWorkingTimes($days, $type, $request = null, $sort = 'asc')
    {
        $desiredDays = [];
        $customDays = $days->map(function ($item) use ($type) {
            $date = getDayByDayCodeV2($item->day_code)['full_date'];
            $item->setAttribute('full_date', $date);
            return $item;
        })->toArray();
        $testArr = [];
        foreach ($customDays as $key => $day) {
            if ($type == 'delivery') {
                $fastDeliveryPreparationTime = config('setting.other.working_times.delivery.preparation_time.fast_delivery') ?? 4;
                $usualDeliveryPreparationTime = config('setting.other.working_times.delivery.preparation_time.usual_delivery') ?? 24;

                $hoursCount = $request->is_fast_delivery == 1 ? ($fastDeliveryPreparationTime ?? 0) : ($usualDeliveryPreparationTime ?? 0);
                $hoursCount = intval($hoursCount);
                // $dayWithHours = now()->addHours($hoursCount); # old way

                ################## Start New Way From Pickup Day and Time #######################
                if (is_null($request->selected_pickup_receiving_date) && is_null($request->selected_pickup_receiving_time)) {
                    $dayWithHours = now()->addHours($hoursCount); # old way
                } else {
                    $pickupDate = $request->selected_pickup_receiving_date;
                    $pickupFullTime = explode('-', $request->selected_pickup_receiving_time);
                    $pickupTimeFrom = $pickupFullTime[0];
                    $pickupTimeTo = $pickupFullTime[1];
                    $pickupCustomTimeTo = explode(':', $pickupTimeFrom);
                    $dayWithHours = Carbon::createFromFormat('Y-m-d', $pickupDate)
                        ->hour($pickupCustomTimeTo[0])
                        ->minute($pickupCustomTimeTo[1])
                        ->second($pickupCustomTimeTo[2])
                        ->addHours($hoursCount);
                }

                ################## End New Way From Pickup Day and Time #######################
                if ($hoursCount < 24 && $dayWithHours->format('Y-m-d') == $day['full_date']) {

                    $newDays = $day['delivery_working_times'];
                    unset($day['delivery_working_times']);

                    foreach ($newDays as $k => $time) {
                        if ($time['to'] >= $dayWithHours->format('H:i:s')) {
                            $day['delivery_working_times'][] = $time;
                        }

                        if (!empty($day['delivery_working_times'])) {
                            $desiredDays[$key] = $day;
                        }
                    }
                } elseif ($hoursCount == 24 && $day['full_date'] >= $dayWithHours->format('Y-m-d')){
                    $updatedDays = $day['delivery_working_times'];
                    unset($day['delivery_working_times']);

                    foreach ($updatedDays as $newKey => $newTime) {
                        if ($newTime['from'] >= $dayWithHours->format('H:i:s') ) {
                            $day['delivery_working_times'][] = $newTime;
                        }else if($day['full_date'] > $dayWithHours->format('Y-m-d')){
                            $day['delivery_working_times'][] = $newTime;
                        }

                        if (!empty($day['delivery_working_times'])) {
                            $desiredDays[$key] = $day;
                        }
                    }
                } else {
                    if ($day['full_date'] >= $dayWithHours->format('Y-m-d')) {
                        $desiredDays[$key] = $day;
                    }
                }

            } else {
                $desiredDays[$key] = $day;
            }
        }

        $days = collect(array_values(collect($desiredDays)->toArray()));

        if ($sort == 'desc') {
            $days = $days->sortByDesc(function ($obj, $key) {
                return strtotime($obj['full_date']);
            })->values();
        } else {
            $days = $days->sortBy(function ($obj, $key) {
                return strtotime($obj['full_date']);
            })->values();
        }
        return $days;
    }

    public function buildDefaultTime($workingDays, $relation, $keys)
    {
        $defaultDay = null;
        $defaultTime = null;
        if (count($workingDays) > 0) {
            $defaultDay = getDayByDayCodeV2($workingDays[0]['day_code']);
            $time = isset($workingDays[0][$relation][0]) ? $workingDays[0][$relation][0] : $workingDays[1][$relation][0];
            $defaultTime = [
                $keys[0] => $time['from'] . '-' . $time['to'],
                $keys[1] => __('From') . ' : ' . date('g:i A', strtotime($time['from'])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time['to'])),
            ];
        }

        return [
            'default_day' => $defaultDay,
            'default_time' => $defaultTime,
            'time_id' => $time['id']
        ];
    }

    public function buildDefaultTimeInUpdate($date, $time, $keys)
    {
        $defaultDay = null;
        $defaultTime = null;
        $date = Carbon::createFromDate($date);
        $dayCode = strtolower($date->format('D'));
        $monthNumber = $date->isoFormat('M');
        $dayNumber = date('d', strtotime($dayCode));
        $defaultDay = [
            'year' => date('Y', strtotime($date)),
            'month_number' => $monthNumber,
            'day_number' => $dayNumber,
            'full_date' => $date->format('Y-m-d'),
            'shorted_translated_date' => translateDate($date, 'shorted'),
            'shorted_translated_month' => $dayNumber . ' ' . getTranslatedMonth()[$monthNumber],
            'translated_date' => translateDate($date),
            'translated_day' => getTranslatedDay()[$dayCode],
            'translated_month' => getTranslatedMonth()[$monthNumber],
        ];
        $defaultTime = [
            $keys[0] => $time[0] . '-' . $time[1],
            $keys[1] => __('From') . ' : ' . date('g:i A', strtotime($time[0])) . ' ' . __('To') . ' : ' . date('g:i A', strtotime($time[1])),
        ];

        return [
            'default_day' => $defaultDay,
            'default_time' => $defaultTime,
        ];
    }

    public function removePreviousTimes($workingDays, $relation)
    {
        $result = [];
        foreach ($workingDays as $key => $value) {
            $times = $value[$relation];
            unset($value[$relation]);
            $result[$key] = $value;
            if ($value['full_date'] == date('Y-m-d')) {
                foreach ($times as $k => $time) {
                    if ($time['from'] >= now()->format('H') + 1) {
                        $result[$key][$relation][$k] = $time;
                    }
                }
                $result[$key][$relation] = array_values($result[$key][$relation] ?? []);
                if (count($result[$key][$relation]) == 0) {
                    unset($result[$key]);
                }
            } else {
                $result[$key][$relation] = $times;
            }
        }

        return collect(array_values($result));
    }

    public function checkDriverTimes($state_id,$workingDays,$relation,$column){
        $drivers = User::whereHas('driverStates',function ($q) use ($state_id){
            $q->where('state_id',$state_id);
        })->get(['id','maximum_received_orders_count']);

        $newDates = [];
        foreach ($drivers as $driver){
            foreach ($workingDays as $day){
                $oneDay=  $day;
                unset($oneDay[$relation]);
                $times = [];
                if(isset($day[$relation])){
                    foreach ($day[$relation] as $time){
                        $count = OrderDriver::where([
                            ['user_id',$driver->id],
                            [$column , $time['id'] ],
                            ['accepted',1],
                            ['delivered',0],
                        ])->count();
                        if($count < $driver->maximum_received_orders_count){
                            $times[$time['id']] = $time;
                        }
                    }
                }
                $oneDay[$relation] = array_values($times);

                $newDates[$day['id']] = $oneDay;
            }
        }

        $newDates = array_values($newDates);
        return $newDates;
    }
}
