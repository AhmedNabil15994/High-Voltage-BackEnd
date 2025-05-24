<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderTime extends Model
{
    protected $table = 'order_times';
    protected $guarded = ["id"];
    protected $casts = [
        'receiving_data' => 'array',
        'delivery_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
