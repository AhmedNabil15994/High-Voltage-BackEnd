<?php

namespace Modules\Catalog\Entities;

use Illuminate\Support\Collection;
use Modules\Slider\Entities\Slider;
use Modules\Core\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;
use Illuminate\Database\Eloquent\SoftDeletes;

use Modules\Advertising\Entities\Advertising;
use Modules\Notification\Entities\GeneralNotification;

class Category extends Model
{
    use HasTranslations, SoftDeletes, ScopesTrait;
    use HasSlugTranslation;

    protected $with = ['children'];
    public $sluggable = 'title';
    protected $guarded = ["id"];
    public $translatable = ['title', 'slug', 'seo_description', 'seo_keywords'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }

    public function getParentsAttribute()
    {
        $parents = collect([]);

        $parent = $this->parent;

        while (!is_null($parent)) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    public function children()
    {
        $categories = $this->hasMany(Category::class, 'category_id');
        if (!is_null(request()->route()) && in_array(request()->route()->getName(), ['api.home', 'frontend.home'])) {
            $categories = $categories->where('show_in_home', 1);
        }
        return $categories;
    }

    public function childrenRecursive()
    {
        return $this->children()->active()->with('childrenRecursive');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'category_id')
            ->has('products')
            ->whereNotNull('categories.category_id');
    }

    public function getAllRecursiveChildren()
    {
        $category = new Collection();
        foreach ($this->children as $cat) {
            $category->push($cat);
            $category = $category->merge($cat->getAllRecursiveChildren());
        }
        return $category;
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
}
