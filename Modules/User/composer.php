<?php

view()->composer([
    'order::dashboard.orders.show',
    'order::dashboard.orders.show-direct',
    'order::vendor.orders.show',
    'order::dashboard.shared._filter',
    'order::vendor.shared._filter',

], \Modules\User\ViewComposers\Dashboard\DriverComposer::class);

view()->composer(['vendor::dashboard.vendors.*'], \Modules\User\ViewComposers\Dashboard\SellerComposer::class);

view()->composer([
    'coupon::dashboard.*',
    'order::dashboard.*',
    'order::vendor.*',
    'baqat::dashboard.baqat_subscriptions.*',
    'report::dashboard.reports._partial.delivered-orders',
], \Modules\User\ViewComposers\Dashboard\UserComposer::class);

view()->composer(
    [
        'user::frontend.profile.index',
        'order::frontend.orders.start-request',
    ],
    \Modules\User\ViewComposers\FrontEnd\UserAddressesComposer::class
);
