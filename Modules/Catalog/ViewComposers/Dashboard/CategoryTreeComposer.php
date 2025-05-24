<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Illuminate\View\View;
use Modules\Catalog\Repositories\Dashboard\CategoryRepository as Category;
use Modules\Catalog\Transformers\Dashboard\CategoryTreeResource;

class CategoryTreeComposer
{
    public $mainCategories;
    public $sharedActiveCategories;
    public $allCategories;

    public function __construct(Category $category)
    {
        $this->mainCategories = $category->mainCategories();
        $this->allCategories = $category->getAll();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['mainCategories' => CategoryTreeResource::collection($this->mainCategories)->jsonSerialize(), 'allCategories' => $this->allCategories->pluck('title', 'id')->toArray()]);
    }
}
