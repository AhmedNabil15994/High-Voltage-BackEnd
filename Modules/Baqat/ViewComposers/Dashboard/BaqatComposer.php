<?php

namespace Modules\Baqat\ViewComposers\Dashboard;

use Modules\Baqat\Repositories\Dashboard\BaqatRepository as Baqat;
use Illuminate\View\View;

class BaqatComposer
{
    public $activeBaqat = [];

    public function __construct(Baqat $baqat)
    {
        $this->activeBaqat =  $baqat->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['activeBaqat' => $this->activeBaqat]);
    }
}
