<?php

Route::group(['prefix' => 'catalog', 'namespace' => 'WebService'], function () {

    Route::get('categories', 'CatalogController@getCategories')->name('api.catalog.categories');
    Route::get('home-categories', 'HomeCategoryController@index')->name('api.catalog.home_categories');
    Route::get('products/autocomplete', 'CatalogController@getAutoCompleteProducts')->name('api.catalog.get_autocomplete_products');
    Route::get('products', 'CatalogController@getProducts')->name('api.catalog.get_products');
    Route::get('filter-data', 'CatalogController@getFilterData')->name('api.catalog.filter');
    Route::get('product/{id}/details', 'CatalogController@getProductDetails')->name('api.catalog.get_product_details');
});
