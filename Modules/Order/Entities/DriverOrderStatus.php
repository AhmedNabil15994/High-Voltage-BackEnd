<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class DriverOrderStatus extends Model
{
    protected $table = 'driver_order_statuses';
    protected $guarded = ['id'];
    public $with = ['driver'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
