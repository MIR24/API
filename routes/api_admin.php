<?php


Route::post('register','API\RegisterController@register');

Route::group([
//    'middleware' => ['mobile', 'auth:api']
], function () {
    Route::apiResource('chanels', 'ChanelsController');
    Route::apiResource('choice', 'ChoiceCategoryController');
    Route::apiResource('categories', 'CategoryController');
});


