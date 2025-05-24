<?php

view()->composer(
    [
        'apps::dashboard.layouts._aside',
    ],
    \Modules\Apps\ViewComposers\Dashboard\StatisticsComposer::class
);

/* view()->composer(
[
'apps::dashboard.working_times.form',
],
\Modules\Apps\ViewComposers\Dashboard\WorkingDaysComposer::class
); */
