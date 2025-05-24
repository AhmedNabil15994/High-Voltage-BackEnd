<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Modules\Catalog\Repositories\Dashboard\CustomAddonRepository as CustomAddon;
use Illuminate\View\View;

class CustomAddonComposer
{
    public $customAddons;

    public function __construct(CustomAddon $customAddon)
    {
        $this->customAddons = $customAddon->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['customAddons' => $this->customAddons]);
    }
}
