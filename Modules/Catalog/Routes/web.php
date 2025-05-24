<?php

/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Catalog', 'Routes/Dashboard')) as $file) {
        require($file->getPathname());
    }*/

    foreach (["categories.php", "products.php", "addon_categories.php", "product_addons.php", "addon_options.php"] as $value) {
        require(module_path('Catalog', 'Routes/Dashboard/' . $value));
    }
});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Catalog', 'Routes/FrontEnd')) as $file) {
        require($file->getPathname());
    }*/

    foreach (["categories.php", "checkout.php", "shopping-cart.php", "products.php"] as $value) {
        require(module_path('Catalog', 'Routes/FrontEnd/' . $value));
    }
});
