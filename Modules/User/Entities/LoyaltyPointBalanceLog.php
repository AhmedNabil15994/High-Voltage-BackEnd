<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;

class LoyaltyPointBalanceLog extends Model
{
    protected $table = 'loyalty_points_balance_logs';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
