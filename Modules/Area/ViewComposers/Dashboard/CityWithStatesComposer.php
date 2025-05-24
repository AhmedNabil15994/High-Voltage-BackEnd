<?php

namespace Modules\Area\ViewComposers\Dashboard;

use Illuminate\View\View;
use Modules\Area\Repositories\Dashboard\CityRepository as City;

class CityWithStatesComposer
{
    public $activeCitiesWithStates = [];

    public function __construct(City $city)
    {
        $this->activeCitiesWithStates = $city->getAllActive('id', 'desc', 1, true); // get all active cities in 'kuwait' country
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['activeCitiesWithStates' => $this->activeCitiesWithStates]);
    }
}
