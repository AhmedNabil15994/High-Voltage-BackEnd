<?php

namespace Modules\Catalog\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductCustomAddon extends Model
{
    protected $table = 'product_custom_addons';
    protected $guarded = ["id"];

    public function addon()
    {
        return $this->belongsTo(CustomAddon::class, 'custom_addon_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
