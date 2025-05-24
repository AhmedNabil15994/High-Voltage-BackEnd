<?php

/*
|================================================================================
|                             Back-END ROUTES
|================================================================================
 */
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    /*foreach (File::allFiles(module_path('Baqat', 'Routes/Dashboard')) as $file) {
    require($file->getPathname());
    }*/

    foreach (["baqat.php", "baqat_subscriptions.php"] as $value) {
        require (module_path('Baqat', 'Routes/Dashboard/' . $value));
    }
});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
 */
Route::prefix('/')->group(function () {

    foreach (["baqat.php"] as $value) {
        require (module_path('Baqat', 'Routes/FrontEnd/' . $value));
    }
});
