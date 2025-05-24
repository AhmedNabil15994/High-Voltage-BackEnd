<?php

namespace Modules\Order\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Cassandra\Custom;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Modules\Apps\Repositories\Dashboard\WorkingTimeRepository as WorkingTimeRepo;
use Modules\Catalog\Entities\CustomAddon;
use Modules\Catalog\Entities\Product as ProductModel;
use Modules\Catalog\Entities\ProductCustomAddon;
use Modules\Catalog\Repositories\Dashboard\CategoryRepository as CategoryRepo;
use Modules\Catalog\Repositories\Dashboard\ProductRepository as Product;
use Modules\Company\Repositories\Dashboard\DeliveryChargeRepository as DeliveryCharge;
use Modules\Core\Traits\DataTable;
use Modules\Notification\Repositories\Dashboard\NotificationRepository as Notification;
use Modules\Notification\Traits\SendNotificationTrait as SendNotification;
use Modules\Order\Constant\OrderStatus as ConstantOrderStatus;
use Modules\Order\Entities\OrderCustomAddon;
use Modules\Order\Http\Requests\Dashboard\OrderDriverRequest;
use Modules\Order\Http\Requests\Dashboard\OrderValidationRequest;
use Modules\Order\Mail\Dashboard\UpdateOrderStatusMail;
use Modules\Order\Repositories\Dashboard\OrderRepository as Order;
use Modules\Order\Repositories\Dashboard\OrderStatusRepository as OrderStatus;
use Modules\Order\Traits\OrderTrait;
use Modules\Order\Transformers\Dashboard\OrderResource;
use Modules\Page\Entities\Page;
use Modules\User\Repositories\Dashboard\AddressRepository as Address;
use Modules\User\Repositories\Dashboard\UserRepository as UserRepo;

class OrderController extends Controller
{
    use SendNotification, OrderTrait;

    protected $order;
    protected $status;
    protected $notification;
    protected $product;
    protected $address;
    protected $deliveryCharge;
    protected $user;
    protected $category;
    protected $workingTime;

    public function __construct(
        Order $order,
        OrderStatus $status,
        Notification $notification,
        Product $product,
        Address $address,
        DeliveryCharge $deliveryCharge,
        UserRepo $user,
        CategoryRepo $category,
        WorkingTimeRepo $workingTime
    ) {
        $this->status = $status;
        $this->order = $order;
        $this->notification = $notification;
        $this->product = $product;
        $this->address = $address;
        $this->deliveryCharge = $deliveryCharge;
        $this->user = $user;
        $this->category = $category;
        $this->workingTime = $workingTime;
    }

    public function index()
    {
        return view('order::dashboard.orders.index');
    }
    public function currentOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['new_order', 'received', 'processing', 'is_ready', 'on_the_way']);
    }


    public function getAllOrders()
    {
        return view('order::dashboard.all_orders.index');
    }
    public function allOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request);
    }


    public function getCompletedOrders()
    {
        return view('order::dashboard.completed_orders.index');
    }
    public function completedOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['delivered']);
    }



    public function getNotCompletedOrders()
    {
        return view('order::dashboard.not_completed_orders.index');
    }
    public function notCompletedOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['failed']);
    }


    public function getRefundedOrders()
    {
        return view('order::dashboard.refunded_orders.index');
    }
    public function refundedOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['refund']);
    }


    public function getNewOrders()
    {
        return view('order::dashboard.new_orders.index');
    }
    public function newOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['new_order', 'received', 'pending']);
    }

    public function getInprogressOrders()
    {
        return view('order::dashboard.inprogress_orders.index');
    }
    public function inprogressOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['processing']);
    }

    public function getReadyOrders()
    {
        return view('order::dashboard.ready_orders.index');
    }
    public function readyOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['is_ready']);
    }

    public function getInwayOrders()
    {
        return view('order::dashboard.inway_orders.index');
    }
    public function inwayOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['on_the_way']);
    }

    public function getDeliveredOrders()
    {
        return view('order::dashboard.delivered_orders.index');
    }
    public function deliveredOrdersDatatable(Request $request)
    {
        return $this->basicDatatable($request, ['delivered']);
    }


    private function basicDatatable($request, $flags = [])
    {
        $datatable = DataTable::drawTable($request, $this->order->customQueryTable($request, $flags), 'orders');
        $datatable['data'] = OrderResource::collection($datatable['data']);
        return Response()->json($datatable);
    }

    public function create(Request $request)
    {
        $order = null;
        $formAction = route('dashboard.orders.store');
        $pageTitle = __('order::dashboard.orders.create.title');
        $categoriesWithProducts = $this->category->getCategoriesWithProducts($request);

        $workingTimes = $this->buildWorkingTimes($request, $this->workingTime->getActivePickupWorkingDays(), $this->workingTime->getActiveDeliveryWorkingDays());
        $pickupWorkingDays = $this->removePreviousTimes($workingTimes['pickupWorkingDays'], 'pickup_working_times');
        $deliveryWorkingDays = $this->removePreviousTimes($workingTimes['deliveryWorkingDays'], 'delivery_working_times');

        $defaultPickupDayTime = $this->buildDefaultTime($pickupWorkingDays, 'pickup_working_times', ['receiving_time', 'receiving_time_text']);
        $selectedPickupFirstDay = $defaultPickupDayTime['default_day'];
        $selectedPickupFirstTime['receiving_time'] = $defaultPickupDayTime['default_time']['receiving_time'];
        $selectedPickupFirstTime['receiving_time_text'] = $defaultPickupDayTime['default_time']['receiving_time_text'];

        $defaultDeliveryDayTime = $this->buildDefaultTime($deliveryWorkingDays, 'delivery_working_times', ['delivery_time', 'delivery_time_text']);
        $selectedDeliveryFirstDay = $defaultDeliveryDayTime['default_day'];
        $selectedDeliveryFirstTime['delivery_time'] = $defaultDeliveryDayTime['default_time']['delivery_time'];
        $selectedDeliveryFirstTime['delivery_time_text'] = $defaultDeliveryDayTime['default_time']['delivery_time_text'];

        return view('order::dashboard.orders.create-edit', compact('pageTitle', 'order', 'formAction', 'categoriesWithProducts', 'pickupWorkingDays', 'deliveryWorkingDays', 'selectedPickupFirstDay', 'selectedPickupFirstTime', 'selectedDeliveryFirstDay', 'selectedDeliveryFirstTime'));
    }

    public function store(OrderValidationRequest $request)
    {
        $user = $this->user->findById($request->user_id);
        $address = $this->address->findById($request->address_id);
        $deliveryCharge = null;
        if ($address) {
            $deliveryCharge = $this->deliveryCharge->findByStateId($address->state_id);
        }

        if (is_null($deliveryCharge)) {
            return Response()->json([false, __('order::frontend.orders.index.alerts.this_state_is_not_supported')]);
        }

        $request->request->add(['state_id' => $address->state_id]);

        if ($request->order_type == 'direct_without_pieces') {
            $order = $this->order->createOrderDirectWithoutPieces($request, $address, $deliveryCharge->delivery, $user);
            if (!$order) {
                return Response()->json([false, __('order::frontend.orders.index.alerts.order_failed')]);
            }

            return Response()->json([true, __('The request has been added successfully!')]);
        } else {
            $result = $this->prepareProductsData($request, $request->is_fast_delivery);
            $order = $this->order->createOrderData($request, $result['allProductAddons'], $deliveryCharge->delivery, $result['subtotal'], $user);
            if (!$order) {
                return Response()->json([false, __('order::frontend.orders.index.alerts.order_failed')]);
            }
            return Response()->json([true, __('The request has been added successfully!')]);
        }
    }

    private function prepareProductsData($request, $is_fast_delivery)
    {
        $result = [];
        $allProductAddons = [];
        $subtotal = 0;
        $qty = $request->qty;
        foreach ($qty as $productId => $addons) {
            $productObject = ProductModel::active()->find($productId);
            if ($productObject) {
                $allProductAddons[$productId] = [
                    'off' => 0,
                    'qty' => 1,
                    'starch' => isset($request->starch[$productId])? $request->starch[$productId] : null,
                ];
                foreach ($addons as $addonId => $qty) {
                    if (intval($qty) > 0) {
                        $customAddon = ProductCustomAddon::with('addon')
                            ->whereHas('addon', function ($query) {
                                $query->active();
                            })
                            ->where('product_id', $productId)
                            ->where('custom_addon_id', $addonId)
                            ->first();

                        if ($customAddon) {
                            $addonPrice = floatval($customAddon->price);
                            if ($is_fast_delivery == true) {
                                $addonPrice = 2 * $addonPrice;
                            }

                            $subtotal += $totalPrice = intval($qty) * $addonPrice;
                            $allProductAddons[$productId]['addons'][] = [
                                'addon_id' => $addonId,
                                'qty' => $qty,
                                'price' => $addonPrice,
                                'total' => $totalPrice,
                            ];
                        }
                    }
                }
            }
        }
        $result['subtotal'] = $subtotal;
        $result['allProductAddons'] = $allProductAddons;
        return $result;
    }

    public function edit(Request $request, $id)
    {
        $locale = locale() == 'ar' ? 'ar_KW' : 'en_US';
        $pageTitle = __('order::dashboard.orders.edit.title');

        $order = $this->order->findById($id);
        if (!$order) {
            abort(404);
        }

        if (in_array($order->order_status_id, [8, 5, 9])) { // is_ready | delivered | on_the_way
            abort(404);
        }

        $formAction = route('dashboard.orders.update', $order->id);
        $userAddresses = $this->address->getUserAddresses($order->user_id);

        /* $receiving_date = $order->orderTimes->receiving_data;
        $delivery_date = $order->orderTimes->delivery_data;
        $receivingDayName = Carbon::parse($receiving_date['receiving_date'])->locale($locale)->dayName;
        $deliveryDayName = Carbon::parse($delivery_date['delivery_date'])->locale($locale)->dayName;
        if (Carbon::today() == Carbon::parse($receiving_date['receiving_date'])) {
        $customReceivingDayName = __('Today');
        } elseif (Carbon::tomorrow() == Carbon::parse($receiving_date['receiving_date'])) {
        $customReceivingDayName = __('Tomorrow');
        } else {
        $customReceivingDayName = $receivingDayName;
        }

        if (Carbon::today() == Carbon::parse($delivery_date['delivery_date'])) {
        $customDeliveryDayName = __('Today');
        } elseif (Carbon::tomorrow() == Carbon::parse($delivery_date['delivery_date'])) {
        $customDeliveryDayName = __('Tomorrow');
        } else {
        $customDeliveryDayName = $deliveryDayName;
        }

        $fullReceivingDate = $customReceivingDayName . ' | ' . $receiving_date['receiving_time'];
        $fullDeliveryDate = $customDeliveryDayName . ' | ' . $delivery_date['delivery_time']; */

        $categoriesWithProducts = $this->category->getCategoriesWithProducts($request);
        $workingTimes = $this->buildWorkingTimes($request, $this->workingTime->getActivePickupWorkingDays(), $this->workingTime->getActiveDeliveryWorkingDays());
        $pickupWorkingDays = $this->removePreviousTimes($workingTimes['pickupWorkingDays'], 'pickup_working_times');
        $deliveryWorkingDays = $this->removePreviousTimes($workingTimes['deliveryWorkingDays'], 'delivery_working_times');

        $date1=$order->orderTimes->receiving_data['receiving_date'];
        $defaultPickupDayTime = $this->buildDefaultTimeInUpdate($date1, explode('-', $order->orderTimes->receiving_data['receiving_time']), ['receiving_time', 'receiving_time_text']);
        $selectedPickupFirstDay = $defaultPickupDayTime['default_day'];
        $selectedPickupFirstTime['receiving_time'] = $defaultPickupDayTime['default_time']['receiving_time'];
        $selectedPickupFirstTime['receiving_time_text'] = $defaultPickupDayTime['default_time']['receiving_time_text'];

        $date2=$order->orderTimes->delivery_data['delivery_date'];
        $defaultDeliveryDayTime = $this->buildDefaultTimeInUpdate($date2, explode('-', $order->orderTimes->delivery_data['delivery_time']), ['delivery_time', 'delivery_time_text']);
        $selectedDeliveryFirstDay = $defaultDeliveryDayTime['default_day'];
        $selectedDeliveryFirstTime['delivery_time'] = $defaultDeliveryDayTime['default_time']['delivery_time'];
        $selectedDeliveryFirstTime['delivery_time_text'] = $defaultDeliveryDayTime['default_time']['delivery_time_text'];

        return view('order::dashboard.orders.create-edit', compact(
            'order',
            'userAddresses',
            'pageTitle',
            /* 'receiving_date',
            'delivery_date',
            'fullReceivingDate',
            'fullDeliveryDate', */
            'formAction',
            'categoriesWithProducts',
            'pickupWorkingDays', 'deliveryWorkingDays', 'selectedPickupFirstDay', 'selectedPickupFirstTime', 'selectedDeliveryFirstDay', 'selectedDeliveryFirstTime'
        ));
    }

    public function update(OrderValidationRequest $request, $id)
    {
        $order = $this->order->findById($id);
        if (!$order) {
            abort(404);
        }
        if (in_array($order->order_status_id, [8, 5])) { // is_ready | (delivered = completed)
            return Response()->json([false, __('order::frontend.orders.index.alerts.cannot_update_order_in_case_of_is_ready')]);
        }

        $user = $this->user->findById($order->user_id);
        $address = $this->address->findById($request->address_id);
        $deliveryCharge = null;
        if ($address) {
            $deliveryCharge = $this->deliveryCharge->findByStateId($address->state_id);
        }
        if (is_null($deliveryCharge)) {
            return Response()->json([false, __('order::frontend.orders.index.alerts.this_state_is_not_supported')]);
        }
        $request->request->add(['state_id' => $address->state_id]);

        if ($request->order_type == 'direct_without_pieces') {
            $checkOrder = $this->order->updateOrderDirectWithoutPieces($request, $address, $deliveryCharge->delivery, $user, $order);
            if (!$checkOrder) {
                return Response()->json([false, __('order::frontend.orders.index.alerts.order_failed')]);
            }

            $result = $this->prepareProductsData($request,$request->is_fast_delivery);
            if (!$result) {
                return Response()->json([false, __('order::frontend.orders.index.alerts.order_failed')]);
            }
            $checkOrder = $this->order->updateOrderData($request, $address, $result['allProductAddons'], $deliveryCharge->delivery, $result['subtotal'], $user, $order);

            return Response()->json([true, __('The request has been updated successfully!')]);
        } else {
            $result = $this->prepareProductsData($request, $request->is_fast_delivery);
            $limit = isset($deliveryCharge) && !is_null($deliveryCharge->min_order_amount) ? $deliveryCharge->min_order_amount  : 0;

            if($limit > ($deliveryCharge->delivery + $result['subtotal'])){
                return Response()->json([false, __('catalog::frontend.cart.min_price') . ' ' . $limit . ' ' . __('KD') ]);
            }

            $checkOrder = $this->order->updateOrderData($request, $address, $result['allProductAddons'], $deliveryCharge->delivery, $result['subtotal'], $user, $order);
            if (!$checkOrder) {
                return Response()->json([false, __('order::frontend.orders.index.alerts.order_failed')]);
            }

            return Response()->json([true, __('The request has been updated successfully!')]);
        }
    }

    public function show($id, $flag = null)
    {
        $order = $this->order->findById($id);
        if (!$order /*|| ($flag != $order->order_flag && $flag != 'all_orders')*/) {
            abort(404);
        }

        $this->order->updateUnread($id);
        $statuses = $this->status->getAll()->whereNotIn('flag', ConstantOrderStatus::BLOCK_CHANGE_STATUS_FLAGS);
        $orderProducts = $order->orderCustomAddons->groupBy('addon_id');
        $termsPage = Page::find(2);

        if ($order->order_type != 'direct_with_pieces') {
            return view('order::dashboard.orders.show-direct', compact('order', 'statuses', 'flag','orderProducts','termsPage'));
        }

        return view('order::dashboard.orders.show', compact('order', 'statuses', 'flag', 'orderProducts','termsPage'));
    }

    public function refundOrder(Request $request, $id)
    {
        $res = $this->order->refundOrderOperation($request, $id);

        if ($res && $res[0] == 0) {

            return Response()->json([false, $res[1]]);
        }

        return Response()->json([true, __('apps::dashboard.general.message_update_success'), 'url' => route('dashboard.orders.show', [$id, 'all_orders'])]);
    }

    public function updateAdminNote(Request $request, $id)
    {
        $order = $this->order->findById($id);
        if ($order) {
            $order->admin_note = $request->admin_note;
            $order->save();
        }
        return Response()->json([true, __('apps::dashboard.general.message_update_success'), 'data' => ['type' => 'admin', 'note' => $request->admin_note]]);
    }

    public function updateClientNote(Request $request, $id)
    {
        $order = $this->order->findById($id);
        if ($order) {
            $order->notes = $request->notes;
            $order->save();
        }
        return Response()->json([true, __('apps::dashboard.general.message_update_success'), 'data' => ['type' => 'client', 'note' => $request->notes]]);
    }

    public function confirmPayment($id)
    {
        $order = $this->order->findById($id);
        $res = $this->order->confirmPayment($order);
        $this->sendNotificationToDrivers($order);
        return redirect()->route('dashboard.orders.show', [$id, 'all_orders']);
    }

    public function cancelPayment($id)
    {
        $res = $this->order->cancelPayment($id);
        return redirect()->route('dashboard.orders.show', [$id, 'all_orders']);
    }

    public function changeOrderStatusToreceived($id)
    {
        $res = $this->order->changeOrderStatusToreceived($id);

        $order = $this->order->findById($id);
        $this->sendNotificationToUser($order->id);
        $this->sendNotificationToDriver($order->id);

        return redirect()->route('dashboard.orders.show', [$id, 'all_orders'])->with(['alert' => 'success', 'status' => __('apps::dashboard.general.message_update_success')]);
    }

    public function changeOrderStatusToProcessing($id)
    {
        $res = $this->order->changeOrderStatusToProcessing($id);

        $order = $this->order->findById($id);
        $this->sendNotificationToUser($order->id);
        // $this->sendNotificationToDriver($order->id);

        return redirect()->route('dashboard.orders.show', [$id, 'all_orders'])->with(['alert' => 'success', 'status' => __('apps::dashboard.general.message_update_success')]);
    }

    public function changeOrderStatusToReady($id)
    {
        $res = $this->order->changeOrderStatusToReady($id);

        $order = $this->order->findById($id);
        // $this->sendNotificationToUser($order->id);
        $this->sendNotificationToDriver($order->id);

        return redirect()->route('dashboard.orders.show', [$id, 'all_orders'])->with(['alert' => 'success', 'status' => __('apps::dashboard.general.message_update_success')]);
    }

    public function changeOrderStatusToOnTheWay($id)
    {
        $res = $this->order->changeOrderStatusToOnTheWay($id);

        $order = $this->order->findById($id);
        $this->sendNotificationToUser($order->id);
        $this->sendNotificationToDriver($order->id);

        return redirect()->route('dashboard.orders.show', [$id, 'all_orders'])->with(['alert' => 'success', 'status' => __('apps::dashboard.general.message_update_success')]);
    }

    public function changeOrderStatusToDelivered($id)
    {
        $res = $this->order->changeOrderStatusToDelivered($id);

        $order = $this->order->findById($id);
        $this->sendNotificationToUser($order->id);
        $this->sendNotificationToDriver($order->id);

        return redirect()->route('dashboard.orders.show', [$id, 'all_orders'])->with(['alert' => 'success', 'status' => __('apps::dashboard.general.message_update_success')]);
    }

    public function updateOrderStatus(OrderDriverRequest $request, $id)
    {
        try {
            $update = $this->order->updateOrderStatusAndDriver($request, $id);

            if ($update) {
                if ($request['user_id']) {
                    ### Start Send E-mail & Push Notification To Mobile App Users ###
                    $this->sendNotificationToUser($id);
                    ### End Send E-mail & Push Notification To Mobile App Users ###
                }

                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function updateBulkOrderStatus(Request $request)
    {
        try {
            $updatedOrder = false;
            foreach ($request['ids'] as $id) {
                $updatedOrder = $this->order->updateOrderStatusAndDriver($request, $id);
                if ($updatedOrder) {

                    ### Start Send E-mail & Push Notification To Mobile App Users ###
                    $this->sendNotificationToUser($id);
                    ### End Send E-mail & Push Notification To Mobile App Users ###

                }
            }

            if ($updatedOrder) {
                return Response()->json([true, __('apps::dashboard.general.message_update_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function destroy($id)
    {
        try {
            $delete = $this->order->delete($id);

            if ($delete) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function deletes(Request $request)
    {
        try {
            if (empty($request['ids'])) {
                return Response()->json([false, __('apps::dashboard.general.select_at_least_one_item')]);
            }

            $deleteSelected = $this->order->deleteSelected($request);
            if ($deleteSelected) {
                return Response()->json([true, __('apps::dashboard.general.message_delete_success')]);
            }

            return Response()->json([false, __('apps::dashboard.general.message_error')]);
        } catch (\PDOException $e) {
            return Response()->json([false, $e->errorInfo[2]]);
        }
    }

    public function printSelectedItems(Request $request)
    {
        try {
            if (isset($request['ids']) && !empty($request['ids'])) {
                $ids = explode(',', $request['ids']);
                $orders = $this->order->getSelectedOrdersById($ids);
                return view('order::dashboard.orders.print', compact('orders'));
            }
            // return Response()->json([false, __('apps::dashboard.general.select_at_least_one_item')]);
        } catch (\PDOException $e) {
            return redirect()->back()->withErrors($e->errorInfo[2]);
        }
    }

    public function sendNotificationToUser($id)
    {
        $order = $this->order->findById($id);
        if (!$order) {
            abort(404);
        }

        $locale = app()->getLocale();
        $tokens = [];
        if (!is_null($order->user_id)) {
            $tokens = $this->notification->getAllUserTokens($order->user_id);
        }
        if (count($tokens) > 0) {
            $data = [
                'title' => __('order::dashboard.orders.notification.title'),
                'body' => __('order::dashboard.orders.notification.body') . ' - ' . $order->orderStatus->title,
                'type' => 'order',
                'id' => $order->id,
            ];

            $this->send($data, $tokens);
        }

        if ($order->user) {
            // Send E-mail to order user
            $email = $order->user->email ?? '';
            Mail::to($email)->send(new UpdateOrderStatusMail($order));
        }

        return true;
    }

    public function sendNotificationToDriver($id)
    {
        $order = $this->order->findById($id);
        if (!$order) {
            abort(404);
        }

        $locale = app()->getLocale();
        $tokens = [];
        $user_id = $order->driver->user_id??null;
        if (!is_null($user_id)) {
            $tokens = $this->notification->getAllUserTokens($user_id);
        }

        if (count($tokens) > 0) {
            $data = [
                'title' => __('order::dashboard.orders.notification.title'),
                'body' => __('order::dashboard.orders.notification.body') . ' - ' . $order->orderStatus->title,
                'type' => 'order',
                'id' => $order->id,
            ];

            $this->send($data, $tokens,'driver_app');
        }

        if ($user_id) {
            // Send E-mail to order user
            $email =  $order->driver->driver->email ?? '';
            Mail::to($email)->send(new UpdateOrderStatusMail($order));
        }

        return true;
    }

}
