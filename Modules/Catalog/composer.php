<?php

// Dashboard ViewComposr
view()->composer([
    'catalog::dashboard.categories.*',
    'catalog::dashboard.products.*',
    'catalog::dashboard.products.index',
    'advertising::dashboard.advertising.*',
    'notification::dashboard.notifications.*',
    'slider::dashboard.sliders.*',
    'coupon::dashboard.*',
], \Modules\Catalog\ViewComposers\Dashboard\CategoryComposer::class);

// Dashboard ViewComposr
view()->composer([
    'advertising::dashboard.advertising.*',
    'notification::dashboard.notifications.*',
    'slider::dashboard.sliders.*',
], \Modules\Catalog\ViewComposers\Dashboard\ProductComposer::class);

view()->composer([
    'coupon::dashboard.*',
], \Modules\Catalog\ViewComposers\Dashboard\ProductComposer::class);

// FrontEnd ViewComposer
view()->composer([
    'apps::frontend.layouts.master',
], \Modules\Catalog\ViewComposers\FrontEnd\CategoryComposer::class);

view()->composer([
    'catalog::dashboard.addon_options.*',
    'catalog::dashboard.products.addons',
], \Modules\Catalog\ViewComposers\Dashboard\AddonCategoryComposer::class);

view()->composer([
    'catalog::dashboard.products.components.categories-tree.tree',
], \Modules\Catalog\ViewComposers\Dashboard\CategoryTreeComposer::class);

view()->composer([
    'catalog::dashboard.products.create',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.clone',
], \Modules\Catalog\ViewComposers\Dashboard\CustomAddonComposer::class);
