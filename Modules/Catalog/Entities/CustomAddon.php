<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class CustomAddon extends Model
{
    use HasTranslations, ScopesTrait, SoftDeletes;

    protected $table = 'custom_addons';
    protected $guarded = ["id"];
    public $translatable = ['title'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_custom_addons')->withPivot(['price', 'qty'])->withTimestamps();
    }
}
