<?php

namespace Modules\Slider\ViewComposers\FrontEnd;

use Illuminate\View\View;
use Modules\Slider\Repositories\FrontEnd\SliderRepository as Slider;

class SliderComposer
{
    public $sliders = [];

    public function __construct(Slider $slider)
    {
        $this->sliders = $slider->getAllActive();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('sliders', $this->sliders);
    }
}
