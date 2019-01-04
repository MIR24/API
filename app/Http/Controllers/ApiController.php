<?php

namespace App\Http\Controllers;

use App\Exceptions\AnswerOldException;
use App\Exceptions\InvalidClientOldException;
use App\Exceptions\OldException;
use App\Exceptions\ServerOldException;
use App\Library\Services\Command\GetListOfCatagories;
use App\Library\Services\Command\GetListOfCountries;
use App\Library\Services\Command\GetListOfNews;
use App\Library\Services\Command\GetNewsById;
use App\Library\Services\Command\GetNewsTextById;
use App\Library\Services\Commands\GetListOfConfig;
use App\Library\Services\Commands\GetListOfPhotos;
use App\Library\Services\TokenValidation\RegistrationUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiController extends BaseController
{
    /**
     * @OA\Schema(
     *   schema="apiRequest",
     *   type="object",
     *   description="Унифицированная форма запроса",
     *   @OA\Property(property="request", type="string",
     *     description="необходимый запрос (auth, categorylist, newslist)"),
     *   @OA\Property(property="options", type="object",
     *     description="набор параметров, специфичный для каждого запроса"),
     *   @OA\Property(property="token", type="string",
     *     description="идентификатор, получаемый после удачной авторизации"),
     * )
     *
     * @OA\Schema(
     *   schema="apiResponse",
     *   type="object",
     *   description="Унифицированная форма ответа",
     *   @OA\Property(property="answer", type="string",
     *     description="операция, указанная в запросе (auth, categorylist, newslist)"),
     *   @OA\Property(property="status", type="string",
     *     description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string",
     *     description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="object",
     *     description="массив запрошенных элементов"),
     * )
     *
     * @OA\Post(
     *   path="/",
     *   summary="Унифицированная форма API. Доступно: categorylist, countries, newsById, newslist",
     *   @OA\RequestBody(
     *       description="Унифицированная форма запроса",
     *       @OA\JsonContent(ref="#/components/schemas/apiRequest"),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Унифицированная форма ответа",
     *      @OA\JsonContent(ref="#/components/schemas/apiResponse")
     *   ),
     * )
     */
    public function index(
        Request $request,
        GetListOfCatagories $getListOfCatagories,
        GetListOfCountries $getListOfCountries,
        GetListOfNews $getListOfNews,
        GetNewsById $getNewsById,
        GetNewsTextById $getNewsTextById,
        RegistrationUser $getRegistrationUser,
        GetListOfConfig $getListConfig,
        GetListOfPhotos $getListOfPhotos
    )
    {

        $validator = Validator::make($request->all(), [
            'request' => ["required", Rule::in(['auth', 'categorylist', 'newslist', 'newsById', 'config', 'text', 'tags', 'gallery', 'push', 'comment', 'types', 'countries'])],
            'options' => 'array',
        ]);

        if ($validator->fails()) {
            throw new InvalidClientOldException($request->get('request') ?? "");
        }

        $responseData = null;

        $operation = $request->get('request');
        $options = $request->get('options');
        $resultOfCommand = [];
        try {
            switch ($operation) {
                case "auth":
                    # Авторизация
                    $resultOfCommand = $getRegistrationUser->handle($options);
                    break;
                case "categorylist":
                    # Категории новостей
                    $resultOfCommand = $getListOfCatagories->handle($options);
                    break;
                case "newslist":
                    # Список новостей
                    $resultOfCommand = $getListOfNews->handle($options);
                    break;
                case "newsById":
                    # Получение новости по её ID
                    $resultOfCommand = $getNewsById->handle($options);
                    break;
                case "config":
                    //TODO GetListOfConfig::handle(array $array) array may be null
                    $resultOfCommand = $getListConfig->handle([]);
                    break;
                case "text":
                    # TODO Получает полный текст новости по ID в двух вариантах – без тегов и с разметкой?
                    # TODO В Java возвращаются одни поля, а в вики другие
                    # TODO В Java: ["title", "hasGallery", "url", "newsText" => ["textWithTags", "textSource", "link"]]
                    # TODO В wiki: ["textWithTags", "textSource"]
                    $resultOfCommand = $getNewsTextById->handle($options);
                    break;
                case "tags":
                    # TODO
                    break;
                case "gallery":
                    $resultOfCommand = $getListOfPhotos->handle($options);
                    break;
                case "push":
                    # TODO
                    break;
                case "comment":
                    # TODO
                    break;
                case "types":
                    # TODO
                    break;
                case "countries":
                    # Запрос списка стран
                    $resultOfCommand = $getListOfCountries->handle($options);
                    break;
                default:
                    throw new AnswerOldException($operation);

            }

            $responseData = $resultOfCommand->getAsArray();

        } catch (\Exception $e) {
            if ($e instanceof OldException) {
                throw $e;
            }

            if (env("APP_DEBUG")) {
                throw new ServerOldException($operation, $e->getMessage());
            } else {
                throw new ServerOldException($operation);
            }
        }

        return response()->json($responseData);

    }
}
