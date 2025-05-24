<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Modules\Advertising\Entities\Advertising;
use Modules\Core\Traits\HasSlugTranslation;
use Modules\Core\Traits\ScopesTrait;
use Modules\Notification\Entities\GeneralNotification;
use Modules\Order\Entities\OrderProduct;
use Modules\Slider\Entities\Slider;
use Modules\Variation\Entities\Option;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasSlugTranslation;
    use HasTranslations, SoftDeletes, ScopesTrait;
    use Sortable;

    protected $with = ['customAddons'];
    protected $guarded = ["id"];
    protected $casts = [];
    public $translatable = [
        'title', 'description', 'slug', 'seo_description', 'seo_keywords',
    ];
    public $sortable = ['id', 'sort', 'created_at', 'updated_at'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at')->where('status', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function subCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->whereNotNull('categories.category_id');
    }

    public function parentCategories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
            ->whereNull('categories.category_id');
    }

    public function offer()
    {
        return $this->hasOne(ProductOffer::class, 'product_id');
    }

    public function addOns()
    {
        return $this->hasMany(ProductAddon::class, 'product_id');
    }

    // variations
    public function options()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductOption::class);
    }

    public function productOptions()
    {
        return $this->belongsToMany(Option::class, 'product_options');
    }

    public function variants()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductVariant::class);
    }

    public function variantChosed()
    {
        return $this->hasOne(\Modules\Variation\Entities\ProductVariant::class);
    }

    public function variantValues()
    {
        return $this->hasMany(\Modules\Variation\Entities\ProductVariantValue::class);
    }

    public function checkIfHaveOption($optionId)
    {
        return $this->variantValues->contains('option_value_id', $optionId);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    public function adverts()
    {
        return $this->morphMany(Advertising::class, 'advertable');
    }

    public function generalNotifications()
    {
        return $this->morphMany(GeneralNotification::class, 'notifiable');
    }

    public function sliders()
    {
        return $this->morphMany(Slider::class, 'sliderable');
    }

    public function customAddons()
    {
        return $this->belongsToMany(CustomAddon::class, 'product_custom_addons')->withPivot(['price', 'qty'])->withTimestamps();
    }
}
