<?php

namespace Modules\Catalog\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Catalog\Repositories\FrontEnd\CategoryRepository as Category;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as Product;

class CategoryController extends Controller
{
    protected $product;
    protected $category;

    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    public function getCategoriesWithProducts(Request $request)
    {
        $categoriesWithProducts = $this->category->getCategoriesWithProducts($request);
        return view('catalog::frontend.categories.category-products', compact('categoriesWithProducts'));
    }
}
