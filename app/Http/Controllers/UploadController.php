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
    /**
     *  @OA\Schema(
     *   schema="apiRequestUploadForSimple",
     *   type="object",
     *   description="Форма запроса  только  для описание, без файла",
     *   @OA\Property(property="title", type="string",
     *     description="Обязательный. Заголовок/название"),
     *   @OA\Property(property="desc", type="string",
     *     description="Обязательный. Описание"),
     *   @OA\Property(property="name", type="string",
     *     description="Обязательный. Имя отправителя"),
     *  @OA\Property(property="profile", type="string",
     *     description="Обязательный. Профиль пользователя"),
     *   @OA\Property(property="token", type="string",
     *     description="Обязательный. Идентификатор, получаемый после удачной авторизации"),
     *  @OA\Property(property="email", type="string",
     *     description="Не обязательный. Электронная почта пользователя."),
     *
     * ),
     *
     *  @OA\Schema(
     *   schema="apiRequestUploadForFile",
     *   type="object",
     *   description="Форма запроса, если загружается файл или видео",
     *   @OA\Property(property="title", type="string",
     *     description="Обязательный. Заголовок/название"),
     *   @OA\Property(property="desc", type="string",
     *     description="Обязательный. Описание"),
     *   @OA\Property(property="name", type="string",
     *     description="Обязательный. Имя отправителя"),
     *  @OA\Property(property="profile", type="string",
     *     description="Обязательный. Профиль пользователя"),
     *   @OA\Property(property="token", type="string",
     *     description="Обязательный. Идентификатор, получаемый после удачной авторизации"),
     *  @OA\Property(property="email", type="string",
     *     description="Не обязательный. Электронная почта пользователя."),
     *  @OA\Property(property="file", type="file",
     *     description="Изображение или видео файл в форматах ('image/png','image/jpeg','image/jpg','video/3gp','video/mpeg','video/mp4')"),
     * ),
     *
     *  @OA\Schema(
     *   schema="apiResponseUploadForSimple",
     *   type="string",
     *   description="Унифицированная форма ответа",
     * ),
     *
     * @OA\Post(
     *   path="/mobile/v1/upload",
     *   tags={"Mobile Api"},
     *   summary="Запрос для записи файдов",
     *
     *   @OA\RequestBody(
     *       description="Унифицированная форма запроса",
     *
     *   @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *          ref="#/components/schemas/apiRequestUploadForFile",
     *       )
     *     ),
     *
     *   @OA\MediaType(
     *       mediaType="application/x-www-form-urlencoded",
     *       @OA\Schema(
     *          ref="#/components/schemas/apiRequestUploadForSimple"
     *       )
     *     ),
     *
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *      description="OK",
     *      @OA\JsonContent(ref="#/components/schemas/apiResponseUploadForSimple", example="Your upload was submitted successfully.")
     *   ),
     *
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden. Проверте токен доступа",
     *      @OA\JsonContent(ref="#/components/schemas/apiResponseUploadForSimple", example="Wrong token.")
     *   ),
     *
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request. Проверте поля обязательные для заполнения, ",
     *      @OA\JsonContent(ref="#/components/schemas/apiResponseUploadForSimple", example="Title not specified.")
     *   ),
     *
     *   @OA\Response(
     *      response=500,
     *      description="Server error. Внутренния ошибка сервера",
     *      @OA\JsonContent(ref="#/components/schemas/apiResponseUploadForSimple", example="Server error")
     *   ),
     * )
     */
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

        if(!$request->files->count()){
            throw new UploadException("multipart/form-data used, but no files found.",400);
        }

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
