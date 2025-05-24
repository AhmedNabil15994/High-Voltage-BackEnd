<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Area\Entities\State;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ScopesTrait;

// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use SoftDeletes, ScopesTrait;
    // use LogsActivity;

    protected $guarded = ['id'];
    protected $appended = [
        'order_flag',
    ];
    protected $casts = [
        'payment_commissions' => 'array',
    ];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /* public function getActivitylogOptions(): LogOptions
    {
    return LogOptions::defaults()
    ->setDescriptionForEvent(fn (string $eventName) => "This model has been : {$eventName}");
    } */

    public function getOrderFlagAttribute()
    {
        $orderStatusFlag = $this->orderStatus->flag ?? '';
        if (in_array($orderStatusFlag, ['new_order', 'received', 'processing', 'is_ready', 'on_the_way'])) {
            return 'current_orders';
        } elseif (in_array($orderStatusFlag, ['delivered'])) {
            return 'completed_orders';
        } elseif (in_array($orderStatusFlag, ['failed'])) {
            return 'not_completed_orders';
        } elseif (in_array($orderStatusFlag, ['refund'])) {
            return 'refunded_orders';
        } else {
            return 'all_orders';
        }
    }

    public function scopeDirectWithPieces($query)
    {
        return $query->where('order_type', 'direct_with_pieces');
    }

    public function scopeDirectWithoutPieces($query)
    {
        return $query->where('order_type', 'direct_without_pieces');
    }

    public function scopeSuccessOrders($query)
    {
        return $query->whereHas('orderStatus', function ($q) {
            $q->where('is_success', 1);
        });
    }

    public function scopeFailedOrders($query)
    {
        return $query->whereHas('orderStatus', function ($q) {
            $q->where('is_success', 0);
        });
    }

    public function transactions()
    {
        return $this->morphOne(\Modules\Transaction\Entities\Transaction::class, 'transaction');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function user()
    {
        return $this->belongsTo(\Modules\User\Entities\User::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id')->where("is_refund", 0);
    }

    public function orderCustomAddons()
    {
        return $this->hasMany(OrderCustomAddon::class, 'order_id');
    }

    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id');
    }

    public function driver()
    {
        return $this->hasOne(OrderDriver::class, 'order_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function driverOrderStatuses()
    {
        return $this->hasMany(DriverOrderStatus::class, 'order_id');
    }

    public function orderTimes()
    {
        return $this->hasOne(OrderTime::class, 'order_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'order_companies')->withPivot('vendor_id', 'availabilities', 'delivery');
    }

    public function orderStatusesHistory()
    {
        return $this->belongsToMany(OrderStatus::class, 'order_statuses_history')->withPivot(['id', 'user_id'])->withTimestamps();
    }

    public function orderCoupons()
    {
        return $this->hasOne(OrderCoupon::class, 'order_id');
    }

    public function subRefund($refund)
    {
        $this->update([
            "original_subtotal" => $this->original_subtotal > $refund ? $this->original_subtotal - $refund : 0,
            "subtotal" => $this->subtotal > $refund ? $this->subtotal - $refund : 0,
            "total" => $this->total > $refund ? $this->total - $refund : 0,

        ]);
    }
}
