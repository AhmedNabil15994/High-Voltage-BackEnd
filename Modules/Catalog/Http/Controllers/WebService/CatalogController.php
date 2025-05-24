<?php

namespace Modules\Catalog\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Catalog\Transformers\WebService\AutoCompleteProductResource;
use Modules\Catalog\Transformers\WebService\FilteredOptionsResource;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Catalog\Transformers\WebService\CategoryResource;
use Modules\Catalog\Repositories\WebService\CatalogRepository as Catalog;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;

use Illuminate\Http\JsonResponse;

class CatalogController extends WebServiceController
{
    protected $catalog;

    function __construct(Catalog $catalog)
    {
        $this->catalog = $catalog;
    }

    public function getCategories(Request $request)
    {
        $items = $this->catalog->getCategories($request);

        if ($request->response_type == 'paginated')
            return $this->responsePagination(CategoryResource::collection($items));
        else
            return $this->response(CategoryResource::collection($items));
    }

    public function getAutoCompleteProducts(Request $request)
    {
        $items = $this->catalog->getAutoCompleteProducts($request);
        $result = AutoCompleteProductResource::collection($items);
        return $this->response($result);
    }

    public function getProducts(Request $request)
    {
        $items = $this->catalog->getProducts($request);

        if ($request->response_type == 'paginated')
            return $this->responsePagination(ProductResource::collection($items));
        else
            return $this->response(ProductResource::collection($items));
    }

    public function getProductDetails(Request $request, $id): JsonResponse
    {
        $product = $this->catalog->getProductDetails($request, $id);
        if ($product) {
            $result['product'] = new ProductResource($product);
            if ($request->with_related_products == 'yes') {
                $result['related_products'] = ProductResource::collection($this->catalog->relatedProducts($product, $request));
            }
            return $this->response($result);
        } else
            return $this->response(null);
    }

    public function getFilterData(Request $request)
    {
        $options = $this->catalog->getFilterOptions($request);
        $result['options'] = FilteredOptionsResource::collection($options);

        return $this->response($result);
    }
}
