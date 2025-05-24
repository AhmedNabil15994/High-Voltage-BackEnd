<?php

namespace Modules\Transaction\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Baqat\Entities\BaqatSubscription;

class BaqatTransaction extends Model
{
    protected $guarded = ['id'];

    public function baqatSubscription()
    {
        return $this->belongsTo(BaqatSubscription::class, 'baqat_subscription_id');
    }
}
