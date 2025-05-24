<?php

namespace Modules\Apps\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

class PickupWorkingTime extends Model
{
    use ScopesTrait;

    protected $guarded = [];
    protected $casts = [
        'id' => 'string',
    ];

    public function pickupWorkDay()
    {
        return $this->belongsTo(PickupWorkingDay::class, 'pickup_working_day_id');
    }
}
