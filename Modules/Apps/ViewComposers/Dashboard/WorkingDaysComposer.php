<?php

namespace Modules\Apps\ViewComposers\Dashboard;

use Illuminate\View\View;
use Modules\Apps\Repositories\Dashboard\WorkingTimeRepository as WorkTimeRepo;

class WorkingDaysComposer
{
    public $workingDays = [];

    public function __construct(WorkTimeRepo $workTime)
    {
        $this->workingDays = $workTime->getAllWorkingDays();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'workingDays' => $this->workingDays,
        ]);
    }
}
