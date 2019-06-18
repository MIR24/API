<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('mobile/v1/upload','UploadController@upload')->middleware('token:upload','mobile');

Route::post('/','ApiController@index')->middleware('token','mobile');

foreach(\App\Http\Controllers\ApiController::$OPERATIONS as $operation ) {
    if ($operation == "comment") {
        Route::post('/' . $operation . '_add', 'ApiController@index')->middleware('token', 'mobile');
        Route::post('/' . $operation . '_get', 'ApiController@index')->middleware('token', 'mobile');
    } else {
        Route::post('/' . $operation, 'ApiController@index')->middleware('token', 'mobile');
    }
}
