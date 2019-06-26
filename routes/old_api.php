<?php

Route::post('/','ApiController@index')->middleware('token','mobile');

foreach(\App\Http\Controllers\ApiController::$OPERATIONS as $operation ) {
    if ($operation == "comment") {
        Route::post('/' . $operation . '_add', 'ApiController@index')->middleware('token', 'mobile');
        Route::post('/' . $operation . '_get', 'ApiController@index')->middleware('token', 'mobile');
    } else {
        Route::post('/' . $operation, 'ApiController@index')->middleware('token', 'mobile');
    }
}

Route::post('/upload','UploadController@upload')->middleware('token:upload','mobile');

Route::match(['get','post'],'/v2/update',function (){
    return "Not implemented";
});