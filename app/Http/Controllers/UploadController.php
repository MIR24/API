<?php

namespace App\Http\Controllers;

use App\Exceptions\UploadException;
use App\Mirreport;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


/**
 * Class UploadController
 * @package App\Http\Controllers
 * Config for this controller config/api_upload.php and config/filesystem.php
 */
class UploadController extends Controller
{

    public function upload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'name' => 'required',
            'profile' => 'required',

        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('title')) {
                throw new UploadException('Title not specified.', 400);
            }
            if ($validator->errors()->has('desc') || $validator->errors()->has('name') || $validator->errors()->has('profile')) {
                throw new UploadException('You must specify description, name and profile and send file', 400);
            }
        }

        if (!$request->hasHeader('Content-Type')) {
            throw new UploadException("Not found headers", 418);
        }
        //TODO may be laravel has more specific method
        if (stripos($request->header('Content-Type'), "multipart/form-data") === 0) {
            return $this->uploadFile($request);

        } elseif ($request->header('Content-Type') === 'application/x-www-form-urlencoded') {
            return $this->uploadData($request);

        } else {

            throw new UploadException("Not found headers", 418);

        }
    }


    public function uploadFile(Request $request)
    {
        /**
         * @var $file UploadedFile
         */

        foreach ($request->file() as $file) {

            if (!in_array($file->getMimeType(), config('api_upload.types'))) {
                throw new UploadException('Not available content type ' . $file->getMimeType(), 400);
            }
            try {

                if ($file->getSize() > config('api_upload.maxFileSize')) {
                    throw new UploadException('Size limit for file exceeded.' . $file->getMimeType(), 400);
                }

                $last = Mirreport::all()->last();

                /**
                 * @var $lastname int
                 */
                $lastname = $last ? $last->id : 0;

                $fileName = Storage::disk('api')->putFileAs('', $file, (++$lastname) . '.' . $file->clientExtension());

                $mirreport = new Mirreport(
                    [
                        'name' => $request->get('name'),
                        'profile' => $request->get('profile'),
                        'title' => $request->get('title'),
                        'filename' => $fileName,
                        'desc' => $request->get('desc'),
                        'email' => $request->get('email'),
                        'date' => new \DateTime()
                    ]
                );

                $mirreport->save();

                Log::info(sprintf(' Uploaded new file. Name: %s  Profile: %s  Email : %s  Title: %s Desc: %s ',
                    $mirreport->name, $mirreport->profile, $mirreport->email, $mirreport->title, $mirreport->desc));

            } catch (\Exception $ex) {
                if ($ex instanceof UploadException)
                    throw $ex;
                else{
                    Log::error($ex->getMessage());
                    throw new UploadException("ServerError", 500);
                }
            }

        }

        return response()->json('Your upload was submitted successfully.');

    }

    public function uploadData(Request $request)
    {

        try {

            $mirreport = new Mirreport(
                [
                    'name' => $request->get('name'),
                    'profile' => $request->get('profile'),
                    'title' => $request->get('title'),
                    //  'filename' => $request->get('filename'),
                    'desc' => $request->get('desc'),
                    'email' => $request->get('email'),
                    'date' => new \DateTime()
                ]
            );

            $mirreport->save();

            Log::info(sprintf(' Uploaded new file. Name: %s  Profile: %s  Email : %s  Title: %s Desc: %s ',
                $mirreport->name, $mirreport->profile, $mirreport->email, $mirreport->title, $mirreport->desc));

        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            throw new UploadException("Server error", 500);
        }

        return response()->json('Your upload was submitted successfully.');
    }
}
