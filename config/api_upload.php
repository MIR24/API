<?php
/**
 * Configuration for download file using api @see  \App\Http\Controllers\UploadController::upload()
 */

return [
    'uploadFolder'=>env('UPLOAD_FOLDER',storage_path()),
    'temp'=>'/tmp'
];