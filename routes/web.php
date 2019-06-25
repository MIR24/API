<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Library\Services\Cache\ResourcesCache;
use App\Library\Services\Resources\ImageRouter;
use App\Library\Services\Resources\VideoRouter;

Route::get('/', function () {
    return redirect('/api/documentation');
});

Route::get('/images/uploaded/{type}{id}.jpg', function ($type, $id, ImageRouter $router) {
    return redirect(
        (new ResourcesCache($router))->addCache(
            "/images/uploaded/{$type}{$id}.jpg/",
            ['type' => $type, 'id' => $id],
            config('cache.images_url_cache_time')
        )
    );
})->where(['id' => '[0-9]+', 'type' => '[a-z,_]+']);

Route::get('/video/content/{videoID}', function ($videoID, VideoRouter $router) {
    return redirect(
        (new ResourcesCache($router))->addCache(
            "/video/content/{$videoID}",
            ['videoID' => $videoID],
            config('cache.video_url_cache_time')
        )
    );
})->where(['videoID' => '[0-9]+']);

Route::post('/v2/update',function (){
    return "Not implemented";
});