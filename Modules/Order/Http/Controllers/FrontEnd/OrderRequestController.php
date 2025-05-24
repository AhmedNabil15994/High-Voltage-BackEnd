<?php

namespace Modules\Order\Http\Controllers\FrontEnd;

use Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Apps\Repositories\Frontend\WorkingTimeRepository as WorkingTimeRepo;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\Company\Repositories\FrontEnd\DeliveryChargeRepository as DeliveryCharge;
use Modules\Order\Entities\OrderDriver;
use Modules\Order\Events\ActivityLog;
use Modules\Order\Http\Requests\FrontEnd\DeliveryTimeRequest;
use Modules\Order\Http\Requests\FrontEnd\StartOrderValidationRequest;
use Modules\Order\Repositories\FrontEnd\OrderRepository as Order;
use Modules\Order\Traits\OrderTrait;
use Modules\User\Entities\User;
use Modules\User\Repositories\FrontEnd\AddressRepository as Address;

class OrderRequestController extends Controller
{
    use ShoppingCartTrait;
    use OrderTrait;

    protected $order;
    protected $product;
    protected $address;
    protected $deliveryCharge;
    protected $workingTime;

    public function __construct(
        Order $order,
        Product $product,
        Address $address,
        DeliveryCharge $deliveryCharge,
        WorkingTimeRepo $workingTime
    ) {
        $this->order = $order;
        $this->product = $product;
        $this->address = $address;
        $this->deliveryCharge = $deliveryCharge;
        $this->workingTime = $workingTime;
    }

    public function index(Request $request)
    {
        $workingTimes = $this->buildWorkingTimes($request, $this->workingTime->getActivePickupWorkingDays(), $this->workingTime->getActiveDeliveryWorkingDays());
        $pickupWorkingDays = $this->removePreviousTimes($workingTimes['pickupWorkingDays'], 'pickup_working_times');
        $deliveryWorkingDays = $this->removePreviousTimes($workingTimes['deliveryWorkingDays'], 'delivery_working_times');

        $defaultPickupDayTime = $this->buildDefaultTime($pickupWorkingDays, 'pickup_working_times', ['receiving_time', 'receiving_time_text']);
        $selectedPickupFirstDay = $defaultPickupDayTime['default_day'];

        $selectedPickupFirstTime['receiving_time'] = ''; //$defaultPickupDayTime['default_time']['receiving_time'];
        $selectedPickupFirstTime['receiving_time_text'] = '';//$defaultPickupDayTime['default_time']['receiving_time_text'];
        $selectedPickupFirstTime['pickup_working_times_id'] = '';//$defaultPickupDayTime['time_id'];

        $defaultDeliveryDayTime = $this->buildDefaultTime($deliveryWorkingDays, 'delivery_working_times', ['delivery_time', 'delivery_time_text']);
        $selectedDeliveryFirstDay = $defaultDeliveryDayTime['default_day'];
        $selectedDeliveryFirstTime['delivery_time'] = '';//$defaultDeliveryDayTime['default_time']['delivery_time'];
        $selectedDeliveryFirstTime['delivery_time_text'] = '';//$defaultDeliveryDayTime['default_time']['delivery_time_text'];
        $selectedDeliveryFirstTime['delivery_working_times_id'] = '';//$defaultDeliveryDayTime['time_id'];
        $pickupWorkingDays = collect(array_values([]));

        return view('order::frontend.orders.start-request',
            compact('pickupWorkingDays', 'deliveryWorkingDays', 'selectedPickupFirstDay', 'selectedPickupFirstTime', 'selectedDeliveryFirstDay', 'selectedDeliveryFirstTime')
        );
    }

    public function saveStartOrderRequest(StartOrderValidationRequest $request)
    {
        $userToken = auth()->id();
        $address = $this->address->findById($request->address_id);
        $deliveryCharge = null;
        if ($address) {
            $deliveryCharge = $this->deliveryCharge->findByStateId($address->state_id);
        }

        if (is_null($deliveryCharge)) {
            return redirect()->back()->withErrors(__('order::frontend.orders.index.alerts.this_state_is_not_supported'));
        }

        $request->request->add(['state_id' => $address->state_id]);

        if ($request->order_type == 'direct_without_pieces') {
            //new conditions
            if (!is_null($request->receiving_date)) {
                if ($request->receiving_date < date('Y-m-d')) {
                    return redirect()->back()->withErrors(__('Pick-up day is not available, choose another day'));
                }
            }

            if (!is_null($request->delivery_date)) {
                if ($request->delivery_date < date('Y-m-d')) {
                    return redirect()->back()->withErrors(__('Delivery day is not available, choose another day'));
                }
            }

            if (!is_null($request->receiving_time) && $request->receiving_date == date('Y-m-d')) {
                $timeFrom = explode('-', $request->receiving_time)[0];
                $timeTo = explode('-', $request->receiving_time)[1];
                if (now()->format('H:i') >= $timeTo) {
                    return redirect()->back()->withErrors(__('Pick-up time is currently not available, choose another time'));
                }
            }

            if (!is_null($request->delivery_time) && $request->delivery_date == date('Y-m-d')) {
                $timeFrom = explode('-', $request->delivery_time)[0];
                $timeTo = explode('-', $request->delivery_time)[1];

                if (now()->format('H:i') >= $timeTo) {
                    return redirect()->back()->withErrors(__('Delivery time is currently not available, choose another time'));
                }
            }

            if (auth()->user()->unPaidOrders->count() > 0) {
                return redirect()->back()->withInput()->with(['unpaid_orders_error' => __('Your are not place order due to previous order not paid, do you want to go to payment page?'), 'alert' => 'danger']);
            }
            //new conditions
            $order = $this->order->createOrderDirectWithoutPieces($request, $address, $deliveryCharge->delivery);
            if (!$order) {
                return redirect()->back()->with([
                    'alert' => 'danger', 'status' => __('order::frontend.orders.index.alerts.order_failed'),
                ]);
            }

            $this->fireLog($order);
            $this->sendNotificationToDrivers($order);
            return view('core::frontend.components._success_order_redirect', ['successMessage' => __('The request has been sent successfully!')]);
        } else {
            $check = $this->setDeliveryCondition($address->state_id, $address->id, $userToken);
            $this->savePiecesOrderCookie($request, $userToken);
            Cart::session($userToken)->clear();
            session(['state_id'=>$address->state_id]);
            session(['order_notes'=> $request->notes]);
            return redirect()->route('frontend.categories.products');
        }
    }

    public function fireLog($order)
    {
        $dashboardUrl = LaravelLocalization::localizeUrl(url(route('dashboard.orders.show', [$order->id, 'current_orders'])));
        $data = [
            'id' => $order->id,
            'type' => 'orders',
            'url' => $dashboardUrl,
            'description_en' => 'New Order',
            'description_ar' => 'طلب جديد ',
        ];
        event(new ActivityLog($data));
    }

    protected function setDeliveryCondition($stateId, $addressId, $userToken)
    {
        $companyId = 1;
        $deliveryFeesObject = $this->deliveryCharge->findByStateAndCompany($stateId, $companyId);
        if (is_null($deliveryFeesObject)) {
            return null;
        }
        $data['price'] = $deliveryFeesObject->delivery;
        $data['address_id'] = $addressId;
        $data['state_id'] = $stateId;

        return $this->companyDeliveryChargeCondition($data, $userToken, $deliveryFeesObject->delivery_time);
    }

    protected function savePiecesOrderCookie($request, $userToken)
    {
        $data = [
            'receiving_date' => $request->receiving_date,
            'delivery_date' => $request->delivery_date,
            'receiving_time' => $request->receiving_time,
            'delivery_time' => $request->delivery_time,
            'receiving_time_text' => $request->receiving_time_text,
            'delivery_time_text' => $request->delivery_time_text,
            'order_type' => $request->order_type,
            'is_fast_delivery' => $request->is_fast_delivery,
            'accept_terms_conditions' => $request->accept_terms_conditions,
            'address_id' => $request->address_id,
            'pickup_working_times_id' => $request->pickup_working_times_id,
            'delivery_working_times_id' => $request->delivery_working_times_id,
        ];
        set_cookie_value('DIRECT_ORDER_COOKIE_' . $userToken, json_encode($data));
    }

    public function getDeliveryDays(DeliveryTimeRequest $request)
    {
        $workingDays = $this->sortWorkingTimes($this->workingTime->getActiveDeliveryWorkingDays(), 'delivery', $request);

        $workingDays = $this->checkDriverTimes($request->state_id,$workingDays,'delivery_working_times','delivery_time_id');

        $defaultDayTime = $this->buildDefaultTime($workingDays, 'delivery_working_times', ['delivery_time', 'delivery_time_text']);
        $selectedDeliveryFirstDay = $defaultDayTime['default_day'];
        $selectedDeliveryFirstTime['delivery_time'] = $defaultDayTime['default_time']['delivery_time'];
        $selectedDeliveryFirstTime['delivery_time_text'] = $defaultDayTime['default_time']['delivery_time_text'];
        $selectedDeliveryFirstTime['delivery_working_times_id'] = $defaultDayTime['time_id'];

        return response()->json([
            "data" => view('order::frontend.orders.partial._delivery_days', compact('workingDays'))->render(),
            'selectedDeliveryFirstDay' => $selectedDeliveryFirstDay,
            'selectedDeliveryFirstTime' => $selectedDeliveryFirstTime,
        ], 200);
    }

}
