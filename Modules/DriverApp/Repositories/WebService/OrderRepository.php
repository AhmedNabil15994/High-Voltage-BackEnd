<?php

namespace Modules\DriverApp\Repositories\WebService;

use Illuminate\Support\Facades\Auth;
use Modules\Catalog\Entities\Product;
use Modules\Order\Entities\Order;
use Modules\User\Entities\DriverState;
use Modules\User\Entities\User;
use Modules\User\Repositories\WebService\AddressRepository;

class OrderRepository
{
    protected $user;
    protected $product;
    protected $order;
    protected $address;

    public function __construct(Order $order, Product $product, AddressRepository $address, User $user)
    {
        $this->product = $product;
        $this->order = $order;
        $this->address = $address;
        $this->user = $user;
    }

    public function getAllByUser($order = 'id', $sort = 'desc')
    {
        $orders = $this->order->with(['orderStatus'])->where('user_id', auth('api')->id())->orderBy($order, $sort)->get();
        return $orders;
    }

    public function getAllByDriver($order = 'id', $sort = 'desc')
    {
        $driverStates = $this->getDriverStates(auth('api')->id()) ?? [];
        $query = $this->order->with(['orderStatus', 'orderCustomAddons.orderProduct', 'orderCustomAddons.addon', 'orderAddress', 'driver']);

        $query = $query->where(function ($query) use ($driverStates) {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    // $query->where('order_type', 'direct_without_pieces');
                    $query->whereNull('payment_status_id');
                });
                $query->orWhere(function ($query) {
                    // $query->where('order_type', 'direct_with_pieces');
                    $query->whereNotIn('payment_status_id', [1, 3]); // pending & failed
                });
            });
            $query->whereHas('orderStatus', function ($query) {
                $query = $query->whereIn('flag', ['new_order', 'delivered', 'is_ready', 'processing', 'received', 'on_the_way']);
            });

            if (auth('api')->user()->can('driver_access')) {
                $query = $query->where(function ($query) use ($driverStates) {
                    $query->whereHas('driver', function ($query) {
                        $query->where('user_id', auth('api')->id());
                    });
                    $query = $query->orWhere(function ($query) use ($driverStates) {
                        $query->doesntHave('driver');
                        $query->whereHas('orderAddress', function ($query) use ($driverStates) {
                            $query->whereIn('state_id', $driverStates);
                        });
                    });
                });
            }

        });
        $query = $query->orderBy($order, $sort)->get();
        return $query;
    }

    public function getPickupByDriver($order = 'id', $sort = 'desc')
    {
        $driverStates = $this->getDriverStates(auth('api')->id()) ?? [];
        $query = $this->order->with(['orderStatus', 'orderCustomAddons.orderProduct', 'orderCustomAddons.addon', 'orderAddress', 'driver']);

        $query = $query->where(function ($query) use ($driverStates) {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    // $query->where('order_type', 'direct_without_pieces');
                    $query->whereNull('payment_status_id');
                });
                $query->orWhere(function ($query) {
                    // $query->where('order_type', 'direct_with_pieces');
                    $query->whereNotIn('payment_status_id', [1, 3]); // pending & failed
                });
            });
            $query->whereHas('orderStatus', function ($query) {
                $query = $query->whereIn('flag', ['new_order']);
            });

            if (auth('api')->user()->can('driver_access')) {
                $query = $query->where(function ($query) use ($driverStates) {
                    $query->whereHas('driver', function ($query) {
                        $query->where('user_id', auth('api')->id());
                    });
                    $query = $query->orWhere(function ($query) use ($driverStates) {
                        $query->doesntHave('driver');
                        $query->whereHas('orderAddress', function ($query) use ($driverStates) {
                            $query->whereIn('state_id', $driverStates);
                        });
                    });
                });
            }

        });
        $query = $query->orderBy($order, $sort)->get();
        return $query;
    }

    public function getDeliveryByDriver($order = 'id', $sort = 'desc')
    {
        $driverStates = $this->getDriverStates(auth('api')->id()) ?? [];
        $query = $this->order->with(['orderStatus', 'orderCustomAddons.orderProduct', 'orderCustomAddons.addon', 'orderAddress', 'driver']);

        $query = $query->where(function ($query) use ($driverStates) {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    // $query->where('order_type', 'direct_without_pieces');
                    $query->whereNull('payment_status_id');
                });
                $query->orWhere(function ($query) {
                    // $query->where('order_type', 'direct_with_pieces');
                    $query->whereNotIn('payment_status_id', [1, 3]); // pending & failed
                });
            });
            $query->whereHas('orderStatus', function ($query) {
                $query = $query->whereIn('flag', ['is_ready', 'on_the_way']);
            });

            if (auth('api')->user()->can('driver_access')) {
                $query = $query->where(function ($query) use ($driverStates) {
                    $query->whereHas('driver', function ($query) {
                        $query->where('user_id', auth('api')->id());
                    });
                    $query = $query->orWhere(function ($query) use ($driverStates) {
                        $query->doesntHave('driver');
                        $query->whereHas('orderAddress', function ($query) use ($driverStates) {
                            $query->whereIn('state_id', $driverStates);
                        });
                    });
                });
            }

        });
        $query = $query->orderBy($order, $sort)->get();
        return $query;
    }

    protected function getDriverStates($id)
    {
        return DriverState::where('status', 1)->where('user_id', $id)->pluck('state_id')->toArray();
    }

    public function findOrderById($id)
    {
        $query = $this->order->with(['orderStatus', 'orderCustomAddons.orderProduct', 'orderCustomAddons.addon', 'orderAddress', 'driver']);

        if (auth('api')->user()->can('driver_access')) {
            $driverStates = $this->getDriverStates(auth('api')->id()) ?? [];

            if (!empty($driverStates)) {
                $query = $query->whereDoesntHave('driver')->whereHas('orderAddress', function ($q) use ($driverStates) {
                    $q->whereIn('state_id', $driverStates);
                });
            } else {
                $query = $query->whereHas('driver', function ($q) {
                    $q->where('user_id', auth('api')->id());
                });
            }
        }
        return $query->find($id);
    }

    public function getDriverPendingOrdersCount()
    {
        $query = $this->order->where(function ($query) {
            $query->whereHas('driver', function ($q) {
                $q->where('user_id', auth('api')->id());
            })->whereHas('orderStatus', function ($query) {
                $query = $query->where('flag', 'new_order');
            });
        });
        return $query->count();
    }

    public function findNewOrderById($id)
    {
        $query = $this->order->query();
        if (auth('api')->user()->can('driver_access')) {
            $query = $query->doesntHave('driver');
        }
        return $query->find($id);
    }

    public function findOrderByDriver($id, $driverId)
    {
        $query = $this->order->query();
        $query = $query->whereHas('driver', function ($q) use ($driverId) {
            $q->where('user_id', $driverId);
        });
        return $query->find($id);
    }

    public function findById($id)
    {
        $order = $this->order->find($id);
        return $order;
    }

    public function findByIdWithUserId($id)
    {
        return $this->order->where('user_id', auth('api')->id())->find($id);
    }

    public function updateOrderByDriver($order, $request, $id)
    {
        $orderData = [];
        $orderStatus = null;
        if (isset($request['accepted']) && !empty($request['accepted'])) {
            if ($request['accepted'] == 1) {
                $orderData['accepted'] = 1;
                $orderStatus = 3; // processing
            } else {
                $orderData['accepted'] = 0;
            }
        }

        if (isset($request['delivered']) && !empty($request['delivered'])) {
            if ($request['delivered'] == 1) {
                $orderData['delivered'] = 1;
                $orderStatus = 5; // delivered
            } else {
                $orderData['delivered'] = 0;
            }
        }

        $orderData['user_id'] = auth('api')->id();
        $order->driver()->create($orderData);

        if (!is_null($orderStatus)) {
            $order->update([
                'order_status_id' => $orderStatus,
            ]);
        }

        return true;
    }

    public function updateOrderStatus($order, $request)
    {
        $orderData = [];
        $check = false;
        $driverId = auth('api')->id();

        if (isset($request['order_status_id']) && !empty($request['order_status_id'])) {
            $orderData['order_status_id'] = $request['order_status_id'];
        }

        if (isset($request['order_notes']) && !empty($request['order_notes'])) {
            $orderData['order_notes'] = $request['order_notes'];
        }

        if (!empty($orderData)) {
            $check = $order->update($orderData);
        }

        if ($request['user_id'] && auth('api')->user()->can('dashboard_access')) {
            $driverId = $request['user_id'];
            $check = $order->driver()->updateOrCreate([
                'user_id' => $request['user_id'],
            ]);
        }

        $order->driverOrderStatuses()->create([
            'user_id' => $driverId,
            'order_status_id' => $orderData['order_status_id'],
        ]);

        return $check;
    }
}
