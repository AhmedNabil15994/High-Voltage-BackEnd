<?php

namespace Modules\Catalog\Repositories\FrontEnd;

use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\CoreTrait;

class ProductRepository
{
    use CoreTrait;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function findBySlug($slug)
    {
        $query = $this->product->active()
            ->with([
                "categories",
                "images",
            ]);

        return $query->anyTranslation('slug', $slug)->first();
    }

    public function findById($id)
    {
        return $this->product->active()->find($id);
    }

    public function checkRouteLocale($model, $slug)
    {
        if ($array = $model->getTranslations("slug")) {
            $locale = array_search($slug, $array);

            return $locale == locale();
        }
        return true;
    }

}
