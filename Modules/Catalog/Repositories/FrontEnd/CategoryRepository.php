<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Modules\Catalog\Entities\Category;
use Modules\Core\Traits\CoreTrait;

class CategoryRepository
{
    use CoreTrait;

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategoriesWithProducts($request, $order = 'sort', $sort = 'asc')
    {
        return $this->category->with(['products' => function ($query) {
            $query->active();
            $query->published();
            $query->has('customAddons')->with(['customAddons']);
        }])->whereHas('products', function ($query) {
            $query->active();
            $query->published();
            $query->has('customAddons');
        })->active()->mainCategories()->orderBy($order, $sort)->get();
    }

}
