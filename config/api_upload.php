<?php
/**
 * Configuration for download file using api @see  \App\Http\Controllers\UploadController::upload()
 */

return [

    'maxFileSize'=>150 *1024 * 1024,

    'temp' => '/tmp',

    'types' => [
        'image/png',
        'image/jpeg',
        'image/jpg',
        'video/3gp',
        'video/mpeg',
        'video/mp4'
    ],

];