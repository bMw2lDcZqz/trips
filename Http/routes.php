<?php

Route::group(['middleware' => ['web', 'lookup:user', 'auth:user'], 'namespace' => 'Modules\Trip\Http\Controllers'], function()
{
    Route::resource('trips', 'TripController');
    Route::post('trips/bulk', 'TripController@bulk');
    Route::get('api/trips', 'TripController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Trip\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('trips', 'TripApiController');
});
