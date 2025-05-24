<?php

namespace Modules\Apps\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;
use Modules\Apps\Http\Requests\FrontEnd\ContactUsRequest;
use Modules\Apps\Notifications\FrontEnd\ContactUsNotification;
use Modules\Apps\Repositories\Frontend\AppHomeRepository as Home;
use Modules\Apps\Transformers\Frontend\HomeFilterResource;
use Modules\Catalog\Repositories\FrontEnd\CategoryRepository as Category;
use Modules\Catalog\Repositories\FrontEnd\ProductRepository as ProductRepo;
use Modules\Slider\Repositories\FrontEnd\SliderRepository as Slider;

class HomeController extends Controller
{
    protected $category;
    protected $slider;
    protected $product;
    protected $home;

    public function __construct(Category $category, Slider $slider, ProductRepo $product, Home $home)
    {
        $this->category = $category;
        $this->slider = $slider;
        $this->product = $product;
        $this->home = $home;
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if (config('setting.other.enable_website') != '1') {
            return view('apps::frontend.landing');
        } else {
            return view('apps::frontend.index');
        }
    }

    /* public function index(Request $request)
    {
    if (config('setting.other.enable_website') != '1') {
    return view('apps::frontend.landing');
    } else {
    ### Get Featured Products
    $featuredProducts = $this->category->getFeaturedProducts($request, ["variants"]);
    ### Get Latest Offers
    $latestOffers = $this->category->getLatestOffersData($request);
    ### Get Main Categories Data
    $categories = $this->category->getMainCategoriesData($request);
    $sliders = $this->slider->getAllActive();

    return view('apps::frontend.index', compact(
    'featuredProducts',
    'latestOffers',
    'categories',
    'sliders',
    ));
    }
    } */

    public function moreItems(Request $request)
    {
        return view('apps::frontend.layouts._more-items');
    }

    public function landing(Request $request)
    {
        return view('apps::frontend.landing');
    }

    public function contactUs()
    {
        return view('apps::frontend.contact-us');
    }

    public function sendContactUs(ContactUsRequest $request)
    {
        Notification::route('mail', config('setting.contact_us.email'))
            ->notify((new ContactUsNotification($request))->locale(locale()));

        return redirect()->back()->with(['status' => __('apps::frontend.contact_us.alerts.send_message')]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function autocompleteProducts(Request $request)
    {
        $results = HomeFilterResource::collection($this->product->autoCompleteSearch($request)->take(30)->get(['title', 'id', 'slug']))->jsonSerialize();
        $response = view('apps::frontend.components.live-search-menu', compact('results'))->render();
        return response()->json(['html' => $response]);
    }
}
