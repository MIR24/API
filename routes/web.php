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

use App\Library\Services\Resources\ImageRouter;

Route::get('/', function () {
    return redirect('/api/documentation');
});
//TODO дабавить разогрев для файлов изображений
Route::get('/images/uploaded/{type}{id}.jpg/', function ($type, $id, ImageRouter $router) {
    return redirect($router->getSrc($id, $type)) ;
})->where(['id' => '[0-9]+', 'type' => '[a-z,_]+']);