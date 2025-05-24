<?php

view()->composer(
    [
        'order::dashboard.shared._filter',
        'order::dashboard.shared._bulk_order_actions',
        'setting::dashboard.tabs.*',
    ],
    \Modules\Order\ViewComposers\Dashboard\OrderStatusComposer::class
);
