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
    return redirect()->away(
        (new ResourcesCache($router))->addCache(
            $_SERVER['REQUEST_URI'],
            ['type' => $type, 'id' => $id],
            config('cache.images_url_cache_time')
        )
    );
})->where(['id' => '[0-9]+', 'type' => '[a-z,_]+']);

Route::get('/v2/media/images/uploaded/{type}{id}.jpg', function ($type, $id, ImageRouter $router) {
    return redirect()->away(
        (new ResourcesCache($router))->addCache(
            $_SERVER['REQUEST_URI'],
            ['type' => $type, 'id' => $id],
            config('cache.images_url_cache_time')
        )
    );
})->where(['id' => '[0-9]+', 'type' => '[a-z,_]+']);

Route::get('/video/content/{videoID}', function ($videoID, VideoRouter $router) {
    return redirect()->away(
        (new ResourcesCache($router))->addCache(
            $_SERVER['REQUEST_URI'],
            ['videoID' => $videoID],
            config('cache.video_url_cache_time')
        )
    );
})->where(['videoID' => '[0-9]+']);