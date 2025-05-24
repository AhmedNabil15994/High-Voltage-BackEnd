<?php

namespace Modules\Baqat\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Baqat\Repositories\FrontEnd\BaqatRepository as BaqatRepo;

class BaqatController extends Controller
{
    protected $baqat;

    public function __construct(BaqatRepo $baqat)
    {
        $this->baqat = $baqat;
    }

    public function index(Request $request)
    {
        $packages = $this->baqat->getAllActive();
        return view('baqat::frontend.packages.index', compact('packages'));
    }

    public function show(Request $request, $id)
    {
        $package = $this->baqat->findById($id);
        if (!$package) {
            abort(404);
        }
        return view('baqat::frontend.packages.purchase-package', compact('package'));
    }
}
