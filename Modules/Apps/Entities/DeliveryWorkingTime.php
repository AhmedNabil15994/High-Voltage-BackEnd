<?php

namespace Modules\Apps\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class DeliveryWorkingTime extends Model
{
    use ScopesTrait;

    protected $guarded = [];
    protected $casts = [
        'id' => 'string',
    ];

    public function deliveryWorkDay()
    {
        return $this->belongsTo(DeliveryWorkingDay::class, 'delivery_working_day_id');
    }
}
