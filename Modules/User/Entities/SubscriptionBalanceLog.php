<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;

class SubscriptionBalanceLog extends Model
{
    protected $table = 'subscription_balance_logs';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
