<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Catalog\Entities\CustomAddon;

class OrderCustomAddon extends Model
{
    protected $table = 'order_custom_addons';
    protected $guarded = ["id"];
    protected $with = ["orderProduct.product", "addon"];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function addon()
    {
        return $this->belongsTo(CustomAddon::class);
    }
}
