<?php

namespace Modules\Catalog\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Modules\Catalog\Transformers\WebService\ProductResource;
use Modules\Catalog\Transformers\WebService\HomeCategoryResource;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\Catalog\Repositories\WebService\HomeCategoryRepository as Repo;

class HomeCategoryController extends WebServiceController
{
    protected $repo;

    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $items = $this->repo->list($request);
        if ($request->response_type == 'paginated')
            return $this->responsePagination(HomeCategoryResource::collection($items));
        else
            return $this->response(HomeCategoryResource::collection($items));
    }

    /* public function listProducts(Request $request)
    {
        return $this->responsePagination(ProductResource::collection(
            $this->repo->listProducts($request)
        ));
    } */
}
