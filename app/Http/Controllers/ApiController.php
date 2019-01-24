<?php

namespace App\Http\Controllers;

use App\Exceptions\AnswerOldException;
use App\Exceptions\InvalidClientOldException;
use App\Exceptions\OldException;
use App\Exceptions\ServerOldException;
use App\Library\Services\Commands\GetComment;
use App\Library\Services\Commands\GetListOfEntityTypesForComment;
use App\Library\Services\Commands\GetListOfCategories;
use App\Library\Services\Commands\GetListOfConfig;
use App\Library\Services\Commands\GetListOfCountries;
use App\Library\Services\Commands\GetListOfNews;
use App\Library\Services\Commands\GetListOfPhotos;
use App\Library\Services\Commands\GetListOfTags;
use App\Library\Services\Commands\GetNewsById;
use App\Library\Services\Commands\GetNewsTextById;
use App\Library\Services\Commands\Push;
use App\Library\Services\Commands\SendComment;
use App\Library\Services\TokenValidation\RegistrationUser;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ApiController extends BaseController
{
    public static $OPERATIONS = [
        'auth',
        'categorylist',
        'newslist',
        'newsById',
        'config',
        'text',
        'tags',
        'gallery',
        'push',
        'comment',
        'types',
        'countries'
    ];

    /**
     * @OA\Tag(
     *     name="Mobile Api",
     *     description="http://wiki.mir24.tv/index.php/Api.mir24.tv",
     * )
     * @OA\Tag(
     *     name="SmartTV",
     * )
     *
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
     * @OA\Schema(
     *   schema="apiRequestCategorylist",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="categorylist"),
     *   @OA\Property(property="options", type="object"),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseCategorylist",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="categorylist", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", example="Total of N categories parsed.", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items( type="object",
     *     @OA\Property(property="id", type="integer", example="95"),
     *     @OA\Property(property="name", type="string", example="ОБЩЕСТВО"),
     *     @OA\Property(property="url", type="string", example="society"),
     *     @OA\Property(property="order", type="integer", example="2"),
     *   ))
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestCountries",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="countries"),
     *   @OA\Property(property="options", type="object"),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseCountries",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="countries", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items( type="object",
     *     @OA\Property(property="id", type="integer", example="4453"),
     *     @OA\Property(property="name", type="string", example="Россия"),
     *   ))
     * )
     */

    /**
     * @OA\Post(
     *   path="/mobile/v1/",
     *   tags={"Mobile Api"},
     *   summary="Унифицированная форма API. Доступно: auth, categorylist, comment, config, countries, gallery, newsById, newslist, tags, text, types",
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
        GetComment $getComment,
        GetListOfEntityTypesForComment $getListOfEntityTypesForComment,
        GetListOfCategories $getListOfCategories,
        GetListOfCountries $getListOfCountries,
        GetListOfNews $getListOfNews,
        GetNewsById $getNewsById,
        GetNewsTextById $getNewsTextById,
        RegistrationUser $getRegistrationUser,
        SendComment $sendComment,
        GetListOfConfig $getListConfig,
        GetListOfPhotos $getListOfPhotos,
        GetListOfTags $getListOfTags,
        Push $push
    ) {

        $validator = Validator::make($request->all(), [
            'request' => [
                "required",
                Rule::in(self::$OPERATIONS)
            ],
            'options' => 'array',
        ]);

        if ($validator->fails()) {
            throw new AnswerOldException($request->get('request') ?? "");
        }

        $responseData = null;

        $operation = $request->get('request'); # TODO if difference with path?
        $options = $request->get('options');
        $options = is_array($options) ? $options : [];
        $resultOfCommand = [];
        try {
            switch ($operation) {
                case "auth":
                    # Авторизация
                    $resultOfCommand = $getRegistrationUser->handle($options);
                    break;
                case "categorylist":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/categorylist",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Категории новостей",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestCategorylist"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Категории новостей",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseCategorylist")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getListOfCategories->handle($options);
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
                    $resultOfCommand = $getListConfig->handle($options);
                    break;
                case "text":
                    # Получает полный текст новости по ID
                    # ["title", "hasGallery", "url", "newsText" => ["textWithTags", "textSource", "link"]]
                    $resultOfCommand = $getNewsTextById->handle($options);
                    break;
                case "tags":
                    $resultOfCommand = $getListOfTags->handle($options);
                    break;
                case "gallery":
                    $resultOfCommand = $getListOfPhotos->handle($options);
                    break;
                case "push":
                    $resultOfCommand = $push->handle($options);
                    break;
                case "comment":
                    if (!isset($options["action"])) {
                        throw new AnswerOldException($operation,
                            sprintf("Required action for operation \"%s\".", $operation));
                    }

                    if ($options["action"] == "add") {
                        $resultOfCommand = $sendComment->handle($options);
                    } elseif ($options["action"] == "get") {
                        $resultOfCommand = $getComment->handle($options);
                    } else {
                        throw new AnswerOldException($operation,
                            sprintf("Unknown action \"%s\" for operation \"%s\".", $options["action"], $operation));
                    }

                    break;
                case "types":
                    # Список типов контента, который комментируется
                    $resultOfCommand = $getListOfEntityTypesForComment->handle($options);
                    break;
                case "countries":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/countries",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Запрос списка стран",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestCountries"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Список стран",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseCountries")
                     *   ),
                     * )
                     */
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
