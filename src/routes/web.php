<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\SiteReviews\Site',
    'middleware' => ['web'],
    'as' => 'site.reviews.',
    'prefix' => siteconf()->get('reviews', "path"),
], function () {
    if (! empty(siteconf()->get('reviews', "path"))) {
        Route::get('/', 'ReviewsController@index')
            ->name('index');
    }
    Route::post('/', 'ReviewsController@store')
        ->name('store');
    Route::post('/answer', "ReviewsController@storeAnswer")
        ->name('store-answer');

    Route::get('/list', 'ReviewsController@list')
        ->name('list');
});
