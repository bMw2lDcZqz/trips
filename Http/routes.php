<?php

Route::group(['middleware' => ['web', 'lookup:user', 'auth:user'], 'namespace' => 'Modules\Trip\Http\Controllers'], function()
{
    Route::resource('trip', 'TripController');
    Route::post('trip/bulk', 'TripController@bulk');
    Route::get('api/trip', 'TripController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Trip\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('trip', 'TripApiController');
});
