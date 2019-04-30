<?php

Route::group([
    'namespace' => 'PortedCheese\SiteReviews\Http\Controllers\Admin',
    'middleware' => ['web', 'role:admin|editor'],
    'as' => 'admin.reviews.',
    'prefix' => 'admin/reviews',
], function () {
    Route::get('/settings', "ReviewsController@settings")
        ->name('settings');
    Route::put('/settings', "ReviewsController@saveSettings")
        ->name('save-settings');

    Route::get('/need-moderate', "ReviewsController@index")
        ->name('need-moderate');
    Route::get('/', "ReviewsController@index")
        ->name('index');

    Route::get('/{review}/edit', "ReviewsController@edit")
        ->name('edit');
    Route::put('/{review}', "ReviewsController@update")
        ->name('update');
    
     Route::get('/{review}', "ReviewsController@show")
         ->name('show');

    Route::delete("/{review}", "ReviewsController@destroy")
        ->name('destroy');

    Route::put("/{review}/moderate", "ReviewsController@changeModerate")
        ->name('moderate');
});

Route::group([
    'namespace' => 'PortedCheese\SiteReviews\Http\Controllers\Site',
    'middleware' => ['web'],
    'as' => 'site.reviews.',
    'prefix' => siteconf()->get('reviews.path'),
], function () {
    Route::get('/', 'ReviewsController@index')
        ->name('index');
    Route::post('/', 'ReviewsController@store')
        ->name('store');
    Route::post('/answer', "ReviewsController@storeAnswer")
        ->name('store-answer');

    Route::get('/list', 'ReviewsController@list')
        ->name('list');
});