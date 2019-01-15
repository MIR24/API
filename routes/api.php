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

Route::post('/', 'ApiController@index')->middleware('token', 'mobile');

Route::prefix('smart/v1/')->group(function () {
//    Route::get('channels/{channelId}', '');
//    Route::get('channels', '');
//    Route::get('channels/{channelId}/sections/{sectionId}', '');
//    Route::get('channels/{channelId}/sections', '');
//    Route::get('broadcasts/{broadcastId}', '');
//    Route::get('broadcasts', '');
//    Route::get('channels/{channelId}/broadcasts/{broadcastId}/episodes/{episodeId}', '');
//    Route::get('episodes', '');
//    Route::get('channels/{channelId}/program', '');
});

Route::prefix('smart/v2/')->group(function () {
    Route::get('categories', 'CategoryController@show');

    Route::get('channels', 'ChannelsController@show');

    Route::get('archives', 'ArchiveController@show');
});
