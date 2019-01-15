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
    # TODO parameters "page" and "limit"
    Route::get('channels', 'ChannelsController@cgetAction');
    Route::get('channels/{channelId}', 'ChannelsController@getAction');
    Route::get('channels/{channelId}/sections', 'SectionController@cgetAction');
    Route::get('sections/{sectionId}', 'SectionController@getAction');
    Route::get('sections/{sectionId}/broadcasts', 'BroadcastController@cgetAction');
    Route::get('broadcasts/{broadcastId}', 'BroadcastController@getAction');
    # TODO Episodes for section
    # TODO Episodes for broadcast
//    Route::get('broadcasts/{broadcastId}/episodes', 'EpisodeController@cgetAction');
    Route::get('episodes/{episodeId}', 'EpisodeController@getAction');
    Route::get('channels/{channelId}/program', 'ChannelsController@getProgramAction');
});

Route::prefix('smart/v2/')->group(function () {
    Route::get('categories', 'CategoryController@show');

    Route::get('channels', 'ChannelsController@show');

    Route::get('archives', 'ArchiveController@show');

    Route::get('episodes/{episodeId}', 'EpisodeController@getV2Action');
});
