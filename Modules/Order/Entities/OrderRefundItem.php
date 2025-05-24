<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Kirschbaum\PowerJoins\PowerJoins;

class OrderRefundItem extends Model
{
    use PowerJoins;
    protected $guarded = ["id"];

    public function item()
    {
        # code...
        return $this->morphTo();
    }

    public function scopeOrderProduct($query)
    {
        return $query->where("item_type", \Modules\Order\Entities\OrderProduct::class);
    }
}
