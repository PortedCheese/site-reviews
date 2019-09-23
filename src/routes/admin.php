<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Vendor\SiteReviews\Admin',
    'middleware' => ['web', 'role:admin|editor'],
    'as' => 'admin.reviews.',
    'prefix' => 'admin/reviews',
], function () {
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