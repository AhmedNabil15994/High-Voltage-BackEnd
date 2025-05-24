<?php

/*
|================================================================================
|                            DRIVER ROUTES
|================================================================================
*/
Route::prefix('driver-dashboard')->middleware(['driver.auth', 'permission:driver_access'])->group(function () {

    foreach (["home.php"] as $value) {
        require(module_path('Apps', 'Routes/Driver/' . $value));
    }

});

/*
|================================================================================
|                            Dashboard ROUTES
|================================================================================
*/
Route::prefix('dashboard')->middleware(['dashboard.auth', 'permission:dashboard_access'])->group(function () {

    foreach (["home.php", "working_times.php"] as $value) {
        require(module_path('Apps', 'Routes/Dashboard/' . $value));
    }

});

/*
|================================================================================
|                             FRONT-END ROUTES
|================================================================================
*/
Route::prefix('/')->group(function () {

    /*foreach (File::allFiles(module_path('Apps', 'Routes/FrontEnd')) as $file) {
        require($file->getPathname());
    }*/

    foreach (["home.php", "contact-us.php"] as $value) {
        require(module_path('Apps', 'Routes/FrontEnd/' . $value));
    }

});
