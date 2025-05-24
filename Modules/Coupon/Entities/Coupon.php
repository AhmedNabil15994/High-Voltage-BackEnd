<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Area\Entities\State;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\ScopesTrait;
use Modules\Order\Entities\OrderCoupon;
use Modules\User\Entities\User;
// use Modules\Vendor\Entities\Vendor;
use Spatie\Translatable\HasTranslations;

class Coupon extends Model
{
    use HasTranslations, SoftDeletes, ScopesTrait;

    protected $with = [];
    protected $guarded = ['id'];
    protected $casts = ['user_type' => 'array'];
    public $translatable = ['title'];

    public function scopeUnexpired($query)
    {
        return $query->where(function ($query) {
            $query->whereNotNull('expired_at');
            $query->where('expired_at', '>', date('Y-m-d'));
        })->orWhere(function ($query) {
            $query->whereNull('expired_at');
        });
    }

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    /* public function vendors()
    {
    return $this->belongsToMany(Vendor::class, 'coupon_vendors')->withTimestamps();
    } */

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_users')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_categories')->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupon_products')->withTimestamps();
    }

    public function states()
    {
        return $this->belongsToMany(State::class, 'coupon_state')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(OrderCoupon::class);
    }

    /*public function vendors()
{
return $this->hasMany(CouponVendor::class);
}

public function users()
{
return $this->hasMany(CouponUser::class);
}

public function categories()
{
return $this->hasMany(CouponCategory::class);
}

public function products()
{
return $this->hasMany(CouponProduct::class);
}*/

}
