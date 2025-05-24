<?php

namespace Modules\Catalog\Repositories\WebService;

use Illuminate\Support\Facades\DB;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\VendorProduct;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Traits\CatalogTrait;
use Modules\Core\Traits\CoreTrait;
use Modules\Variation\Entities\Option;
use Modules\Variation\Entities\ProductVariant;
use Modules\Vendor\Entities\Vendor;

class CatalogRepository
{
    use CatalogTrait, CoreTrait;

    protected $category;
    protected $product;
    protected $vendor;
    protected $prd;
    protected $prdVariant;
    protected $option;
    protected $defaultVendor;

    function __construct(
        VendorProduct  $product,
        Product        $prd,
        Category       $category,
        Vendor         $vendor,
        ProductVariant $prdVariant,
        Option         $option
    ) {
        $this->category = $category;
        $this->product = $product;
        $this->vendor = $vendor;
        $this->prd = $prd;
        $this->prdVariant = $prdVariant;
        $this->option = $option;

        $this->defaultVendor = null;
    }

    public function getCategories($request)
    {
        $query = $this->category->active()->mainCategories();

        if ($request->show_in_home == 1)
            $query = $query->where('show_in_home', 1);

        if ($request->model_flag == 'tree')
            $query = $query->with('childrenRecursive');

        $query = $query->whereHas('products', function ($query) use ($request) {
            $query->active();
            $query = $this->vendorOfProductQueryCondition($query, $request->state_id, $request->vendor_id);
        });

        $query = $query->orderBy('sort', 'asc');

        if ($request->response_type == 'paginated')
            $query = $query->paginate($request->count ?? 24);
        else {
            if (!empty($request->count))
                $query = $query->take($request->count);
            $query = $query->get();
        }

        return $query;
    }

    public function getFilterOptions($request)
    {
        return $this->option->active()
            ->with(['values' => function ($query) {
                $query->active();
            }])
            ->activeInFilter()
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function getProducts($request)
    {
        $allCats = $this->getAllSubCategoryIds($request->category_id);
        array_push($allCats, intval($request->category_id));
        $optionsValues = isset($request->options_values) && !empty($request->options_values) ? array_values($request->options_values) : [];
        $optionsValues = $this->removeEmptyValuesFromArray($optionsValues);

        $query = $this->prd->active()
            ->with([
                'offer' => function ($query) {
                    $query->active()->unexpired()->started();
                },
            ])
            ->with(['variants' => function ($q) {
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            }]);

        $query = $this->vendorOfProductQueryCondition($query, $request->state_id, $request->vendor_id);

        if ($request->category_id) {
            $query = $query->whereHas('categories', function ($query) use ($allCats) {
                $query->whereIn('product_categories.category_id', $allCats);
            });
        }

        if ($request->tag_id) {
            $query = $query->whereHas('tags', function ($query) use ($request) {
                $query->where('product_tags.tag_id', $request->tag_id);
            });
        }

        if (count($optionsValues) > 0) {
            $query = $query->whereHas('variantValues', function ($query) use ($optionsValues) {
                $query->whereIn('option_value_id', $optionsValues);
            });
        }

        if ($request->get_offers == 'offers') {
            $query = $query->whereHas('offer', function ($query) {
                $query->active()->unexpired()->started();
            });
        } elseif ($request->get_offers == 'no_offers') {
            $query = $query->doesnthave('offer');
        }

        if ($request->is_new == 'yes') {
            $query = $query->where('is_new', 1);
        } elseif ($request->is_new == 'no') {
            $query = $query->where('is_new', 0);
        }

        if ($request['low_price'] && $request['high_price']) {
            $query = $query->whereBetween('price', [$request['low_price'], $request['high_price']]);
        }

        if ($request['search']) {
            $query = $this->productSearch($query, $request);
        }

        if ($request['sort']) {
            $query = $query->when($request['sort'] == 'a_to_z', function ($query) {
                $query->orderBy('title->' . locale(), 'asc');
            })->when($request['sort'] == 'z_to_a', function ($query) {
                $query->orderBy('title->' . locale(), 'desc');
            })->when($request['sort'] == 'low_to_high', function ($query) {
                $query->orderBy('price', 'asc');
            })->when($request['sort'] == 'high_to_low', function ($query) {
                $query->orderBy('price', 'desc');
            });
        } else {
            $query->orderBy('id', 'DESC');
        }

        if ($request->response_type == 'paginated')
            $query = $query->paginate($request->count ?? 24);
        else {
            if (!empty($request->count))
                $query = $query->take($request->count);
            $query = $query->get();
        }

        return $query;
    }

    public function relatedProducts($selectedProduct, $request = null)
    {
        $relatedCategoriesIds = $selectedProduct->categories()->pluck('product_categories.category_id')->toArray();
        $query = $this->prd->where('id', '<>', $selectedProduct->id)->active();
        $query = $query->whereHas('categories', function ($query) use ($relatedCategoriesIds) {
            $query->whereIn('product_categories.category_id', $relatedCategoriesIds);
        });
        $query = $this->vendorOfProductQueryCondition($query, $request->state_id, $request->vendor_id);

        $query = $query->orderBy('id', 'desc');

        if (!empty($request->related_products_count))
            $query = $query->take($request->related_products_count);

        return $query->get();
    }

    public function findOneProduct($id)
    {
        $query = $this->prd->active();
        $query = $this->vendorOfProductQueryCondition($query);
        $query = $this->returnProductRelations($query, null);
        return $query->find($id);
    }

    public function findOneProductVariant($id)
    {
        $product = $this->prdVariant->active()->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'productValues', 'product',
        ]);

        $product = $product->whereHas('product', function ($query) {
            $query->active();
            $query = $this->vendorOfProductQueryCondition($query);
        });
        return $product->find($id);
    }

    public function getAutoCompleteProducts($request)
    {
        $query = $this->prd->active();
        $query = $this->vendorOfProductQueryCondition($query, $request->state_id);
        if ($request['search']) {
            $query = $this->productSearch($query, $request);
        }
        return $query->orderBy('id', 'DESC')->get();
    }

    public function getProductDetails($request, $id)
    {
        $query = $this->prd->active();
        $query = $this->vendorOfProductQueryCondition($query);
        $query = $this->returnProductRelations($query, $request);
        return $query->find($id);
    }

    public function productSearch($model, $request)
    {
        $term = strtolower($request['search']);
        return $model->where(function ($query) use ($term) {
            $query->whereRaw('lower(sku) like (?)', ["%{$term}%"]);
            $query->orWhereRaw('lower(title) like (?)', ["%{$term}%"]);
            $query->orWhereRaw('lower(slug) like (?)', ["%{$term}%"]);
        });

        /* ->orWhere(function ($query) use ($request) {
            foreach (config('translatable.locales') as $code) {
                $query->orWhere('title->' . $code, 'like', '%' . $request['search'] . '%');
                $query->orWhere('slug->' . $code, 'like', '%' . $request['search'] . '%');
            }
        }); */
    }

    public function returnProductRelations($model, $request)
    {
        return $model->with([
            'offer' => function ($query) {
                $query->active()->unexpired()->started();
            },
            'options',
            'images',
            'vendor',
            'subCategories',
            'addOns',
            'variants' => function ($q) {
                $q->active();
                $q->with(['offer' => function ($q) {
                    $q->active()->unexpired()->started();
                }]);
            },
        ]);
    }
}
