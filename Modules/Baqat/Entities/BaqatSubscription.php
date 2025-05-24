<?php

namespace Modules\Baqat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\ScopesTrait;
use Modules\Order\Entities\PaymentStatus;
use Modules\Transaction\Entities\BaqatTransaction;
use Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class BaqatSubscription extends Model
{
    use SoftDeletes, ScopesTrait;

    public $table = "baqat_subscriptions";
    protected $guarded = ["id"];

    public static $groupedLastScopeFields = ['user_id'];

    public function scopeUnexpired($query)
    {
        return $query->where('start_at', '<=', date('Y-m-d'))->where(function ($q){
            $q->where([
                ['end_at', '>=', date('Y-m-d')],
                ['new_end_at',null],
            ])->orWhere([
                ['new_end_at', '>=', date('Y-m-d')]
            ]);
        });
    }

    public function scopeEnded($query)
    {
        return $query->where(function ($q){
            $q->where('end_at', '<', date('Y-m-d'))->orWhere('new_end_at', '<', date('Y-m-d'));
        });
    }

    public function scopeSuccessSubscriptions($query)
    {
        return $query->whereNotNull('payment_confirmed_at')->whereHas('paymentStatus', function ($query) {
            $query->whereIn('flag', ['cash', 'success']);
        });
    }

    public function scopeSumActive($query) {
        return $query->Unexpired()->sum('price');
    }
    public function scopeCountActive($query) {
        return $query->Unexpired()->count();
    }

    public function scopeSumInActive($query) {
        return $query->Ended()->sum('price');
    }
    public function scopeCountInActive($query) {
        return $query->Ended()->count();
    }

    public function baqa()
    {
        return $this->belongsTo(Baqat::class, 'baqat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->hasOne(BaqatTransaction::class, 'baqat_subscription_id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function scopeLastPerGroup(Builder $query, ?array $fields = null) : Builder
    {
        return $query->whereIn('id', function (QueryBuilder $query) use ($fields) {
            return $query->from(static::getTable())
                ->selectRaw('max(`id`)')
                ->groupBy($fields ?? self::$groupedLastScopeFields);
        });
    }
}
