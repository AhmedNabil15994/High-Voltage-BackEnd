<?php

view()->composer([
    'baqat::dashboard.baqat_subscriptions.create',
    'baqat::dashboard.baqat_subscriptions.index',
    'baqat::dashboard.baqat_subscriptions.current.index',
], \Modules\Baqat\ViewComposers\Dashboard\BaqatComposer::class);
