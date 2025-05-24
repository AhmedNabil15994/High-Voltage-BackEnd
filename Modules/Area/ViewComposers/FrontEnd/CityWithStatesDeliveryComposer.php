<?php

namespace Modules\Area\ViewComposers\FrontEnd;

use Illuminate\View\View;
use Modules\Area\Repositories\FrontEnd\CityRepository as City;

class CityWithStatesDeliveryComposer
{
    public $citiesWithStatesDelivery;

    public function __construct(City $city)
    {
        $this->citiesWithStatesDelivery = $city->getCitiesWithStatesDelivery();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['citiesWithStatesDelivery' => $this->citiesWithStatesDelivery]);
    }
}
