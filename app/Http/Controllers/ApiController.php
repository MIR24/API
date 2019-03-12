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
    private const PATH_BEGIN = "api/mobile/v1";

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
     *   schema="apiRequestAuth",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="auth"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="login", type="string"),
     *     @OA\Property(property="pass", type="string"),
     *   ),
     * )
     * @OA\Schema(
     *   schema="apiResponseAuth",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="auth", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items( type="object",
     *     @OA\Property(property="token", type="string"),
     *   ))
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
     *   schema="apiRequestCommentAdd",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="comment"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="action", type="string", example="add"),
     *     @OA\Property(property="name", type="string", example="Виталий Кличко"),
     *     @OA\Property(property="profile", type="string", example="http://vk.com/kli4ko"),
     *     @OA\Property(property="email", type="email", example="kli4ko@gmail.ua"),
     *     @OA\Property(property="entityID", type="string", example="10124295",
     *       description="ID новости, фотографии или видео (в зависимости от параметра type)"
     *     ),
     *     @OA\Property(property="type", type="integer", example=0,
     *       description="тип контента, который комментируется (список типов с id получается методом types 0 – новости, 1 –фото, 2 – видео)."
     *     ),
     *     @OA\Property(property="text", type="string", example="Я не боюсь заходит в дверь. Я буду заходить туда, куда готов.")
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseCommentAdd",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="comment", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="object")
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestCommentGet",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="comment"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="action", type="string", example="get"),
     *     @OA\Property(property="entityID", type="string", example="10124295",
     *       description="ID новости, фотографии или видео (в зависимости от параметра type)"
     *     ),
     *     @OA\Property(property="type", type="integer", example=0,
     *       description="тип контента"
     *     ),
     *     @OA\Property(property="page", type="integer", example=1),
     *     @OA\Property(property="limit", type="integer", example=20),
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseCommentGet",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="comment", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items(type="object",
     *     @OA\Property(property="id", type="integer", example="2"),
     *     @OA\Property(property="name", type="string", example="Виталий Кличко"),
     *     @OA\Property(property="profile", type="string", example="http://vk.com/kli4ko"),
     *     @OA\Property(property="email", type="email", example="kli4ko@gmail.ua"),
     *     @OA\Property(property="time", type="string", example="2019-01-29 12:52:13"),
     *     @OA\Property(property="text", type="string", example="Я не боюсь заходит в дверь. Я буду заходить туда, куда готов.")
     *   ))
     * )
     * @OA\Schema(
     *   schema="apiRequestConfig",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="config"),
     *   @OA\Property(property="options", type="object"),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseConfig",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="config", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items( type="object",
     *     @OA\Property(property="imageBaseURL", type="string", example="http://mir24.tv/media/images/uploaded/"),
     *     @OA\Property(property="videoBaseURL", type="string", example="http://stc01.mir24.tv/video/content/"),
     *     @OA\Property(property="streamURLAndroid", type="string", example="http://api.mir24.tv/v2/media/images/uploaded/"),
     *     @OA\Property(property="imegeTypes", type="array", @OA\Items( type="object",
     *       @OA\Property(property="alias", type="string", example="gallery"),
     *       @OA\Property(property="size", type="string", example="110x68"),
     *     )),
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
     *
     * @OA\Schema(
     *   schema="apiRequestGallery",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="gallery"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="newsID", type="integer", example="16318155"),
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseGallery",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="gallery", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items( type="object",
     *     @OA\Property(property="id", type="integer", example="16306523"),
     *   ))
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestNewsById",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="newsById"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="newsID", type="integer", example="16318155"),
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseNewsById",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="newsById", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="object",
     *     @OA\Property(property="id", type="integer", example=11130270),
     *     @OA\Property(property="date", type="date", example="Aug 28, 2014 3:18:39 PM"),
     *     @OA\Property(property="title", type="string", example="Постпред РФ при ОБСЕ: российских военных на Украине нет"),
     *     @OA\Property(property="shortText", type="string", example="Андрей Келин опровергает заявления Киева о введении российских войск на украинскую территорию.",
     *       description="краткое описание с тегами",
     *     ),
     *     @OA\Property(property="shortTextSrc", type="string", example="Андрей Келин опровергает заявления Киева о введении российских войск на украинскую территорию.",
     *       description="краткое описание без тегов",
     *     ),
     *     @OA\Property(property="imageID", type="integer", example=11130295,
     *       description="id изображения на сайте (0 – его отсутствие)",
     *     ),
     *     @OA\Property(property="categoryID", type="integer", example=246),
     *     @OA\Property(property="videoID", type="integer", example=11130591),
     *     @OA\Property(property="copyright", type="string", description="копирайт фото новости" ),
     *     @OA\Property(property="copyrightSrc", type="string"),
     *     @OA\Property(property="rushHourNews", type="boolean", example=true, description="является ли «новостью часа»" ),
     *     @OA\Property(property="topListNews", type="boolean", example=false, description="главная в рубрике новость" ),
     *     @OA\Property(property="hasGallery", type="boolean", example=false, description="есть ли галерея изображений в новости" ),
     *     @OA\Property(property="textWithTags", type="string", example="text"),
     *     @OA\Property(property="textSource", type="string", example="text"),
     *     @OA\Property(property="videoDuration", type="string", example="00:03:21.00"),
     *   )
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestNewsList",
     *   type="object",
     *   description="Параметры onlyVideo, onlyWithGallery, lastNews (limit новостей из каждой категории) – являются необязательными (при false – можно не указывать) и взаимоисключающими.
     *      actual – прислать последние limit новостей из раздела актуальное (lastNews должно быть отключено).",
     *   @OA\Property(property="request", type="string", example="newslist"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="page", type="integer", example=1),
     *     @OA\Property(property="limit", type="integer", example=20),
     *     @OA\Property(property="actual", type="boolean", example=false),
     *     @OA\Property(property="lastNews", type="boolean", example=false),
     *     @OA\Property(property="newsID", type="integer"),
     *     @OA\Property(property="category", type="integer"),
     *     @OA\Property(property="countryID", type="boolean", example=4053),
     *     @OA\Property(property="tags", type="array", @OA\Items(type="integer", example=6480068)),
     *     @OA\Property(property="onlyVideo", type="boolean", example=false),
     *     @OA\Property(property="onlyWithGallery", type="boolean", example=false)
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseNewsList",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="newslist", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="object",
     *     @OA\Property(property="id", type="integer", example=11130270),
     *     @OA\Property(property="date", type="date", example="Aug 28, 2014 3:18:39 PM"),
     *     @OA\Property(property="title", type="string", example="Постпред РФ при ОБСЕ: российских военных на Украине нет"),
     *     @OA\Property(property="shortText", type="string", example="Андрей Келин опровергает заявления Киева о введении российских войск на украинскую территорию.",
     *       description="краткое описание с тегами",
     *     ),
     *     @OA\Property(property="shortTextSrc", type="string", example="Андрей Келин опровергает заявления Киева о введении российских войск на украинскую территорию.",
     *       description="краткое описание без тегов",
     *     ),
     *     @OA\Property(property="imageID", type="integer", example=11130295,
     *       description="id изображения на сайте (0 – его отсутствие)",
     *     ),
     *     @OA\Property(property="categoryID", type="integer", example=246),
     *     @OA\Property(property="videoID", type="integer", example=11130591),
     *     @OA\Property(property="copyright", type="string", description="копирайт фото новости"),
     *     @OA\Property(property="copyrightSrc", type="string"),
     *     @OA\Property(property="rushHourNews", type="boolean", example=true, description="является ли «новостью часа»"),
     *     @OA\Property(property="topListNews", type="boolean", example=false, description="главная в рубрике новость"),
     *     @OA\Property(property="hasGallery", type="boolean", example=false, description="есть ли галерея изображений в новости"),
     *     @OA\Property(property="videoDuration", type="string", example="00:03:21.00"),
     *   )
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestNewsText",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="text"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="newsID", type="integer", example="16318155"),
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseNewsText",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="text", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="object",
     *     @OA\Property(property="textWithTags", type="string"),
     *     @OA\Property(property="textSource", type="string"),
     *   )
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestPush",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="push"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="token", type="string", description="токен PUSH-уведомлений."),
     *     @OA\Property(property="type", type="integer", example="apn", description="тип должен быть GCM или APN"),
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponsePush",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="push", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="object")
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestTags",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="tags"),
     *   @OA\Property(property="options", type="object",
     *     @OA\Property(property="sortType", type="string", example="top",
     *       description="селектор для выбора типа тегов – последние или популярные (actual или top)"),
     *     @OA\Property(property="page", type="integer", example="1",
     *       description="page – выборка страницы тегов (1 = первые 100 тегов, 2  = вторые 100 тегов и т.д.)"),
     *     @OA\Property(property="tagsID", type="array", @OA\Items( type="integer", example="15363718"),
     *       description="получить соответствие id:tag по заданным id тегов. Параметр исключает остальные options"
     *     ),
     *   ),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseTags",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="tags", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array", @OA\Items( type="object",
     *     @OA\Property(property="id", type="integer", example="15364446"),
     *     @OA\Property(property="name", type="string", example="КРАСИВЫЙ ГОЛ"),
     *   ))
     * )
     *
     * @OA\Schema(
     *   schema="apiRequestTypesForComment",
     *   type="object",
     *   @OA\Property(property="request", type="string", example="types"),
     *   @OA\Property(property="options", type="object"),
     *   @OA\Property(property="token", type="string", description="идентификатор, получаемый после удачной авторизации"),
     * )
     * @OA\Schema(
     *   schema="apiResponseTypesForComment",
     *   type="object",
     *   @OA\Property(property="answer", type="string", example="tags", description="операция"),
     *   @OA\Property(property="status", type="string", example="200", description="числовой результат выполнения операции, по аналогии с кодами состояния HTTP (200 – OK, 400 – CLIENT ERROR, 403 – RESTRICTED, 500 –SERVER ERROR)"),
     *   @OA\Property(property="message", type="string", description="комментарий к выполнению операции или сообщение об ошибке"),
     *   @OA\Property(property="content", type="array",
     *     description="cписок типов с id: 0 – новости, 1 – фото, 2 – видео",
     *     @OA\Items( type="object",
     *       @OA\Property(property="id", type="integer", example="0"),
     *       @OA\Property(property="name", type="string", example="news"),
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

        $operation = $request->get('request');
        $options = $request->get('options');
        $options = is_array($options) ? $options : [];
        $resultOfCommand = [];
        try {
            $this->validateOperation($request, $operation);

            switch ($operation) {
                case "auth":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/auth",
                     *   tags={"Mobile Api"},
                     *   summary="Возвращает токен",
                     *   description="При успешной авторизации возвращается токен, который затем должен передаваться в других запросах.",
                     *   @OA\RequestBody(
                     *       description="Авторизация",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestAuth"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Токен после успешной авторизации",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseAuth")
                     *   ),
                     * )
                     */
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
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/newslist",
                     *   tags={"Mobile Api"},
                     *   description="<p>Все параметры необязательны.</p>
                    <p>Если <strong>lastNews</strong> == true, то пытаемся возвратить новости из кеша,
                    учитывая параметры <em>page, limit, category, countryID, onlyVideo и onlyWithGallery</em>,
                    но <strong>игнорируя параметры newsID, tags, actual</strong>:
                    </p>
                    <ol>
                    <li>если это первая страница и не указаны страна и категория
                    - новости берутся из кеша с учётом флагов onlyVideo, onlyWithGallery
                    (если установлены оба, то используется onlyVideo).
                    </li>
                    <li>если указаны страна и категория, не указаны ни onlyVideo, ни onlyWithGallery -
                    новости берутся из кеша с учётом page и limit.</li>
                    </ol>
                    <p>Новости ищутся в БД с учётом <strong>page, limit, newsID, category, countryID, onlyVideo, onlyWithGallery</strong> и <strong>tags</strong>.
                    Если при этом <strong>actual</strong> == true,
                    то в БД ищем только те новости, которые зарегистрированы в таблице actual_news(promo).
                    </p>
                    ",
                     *   @OA\RequestBody(
                     *       description="Запрос списка новостей",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestNewsList"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Список новостей",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseNewsList")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getListOfNews->handle($options);
                    break;
                case "newsById":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/newsById",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Получение новости по её ID",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestNewsById"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Новость",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseNewsById")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getNewsById->handle($options);
                    break;
                case "config":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/config",
                     *   tags={"Mobile Api"},
                     *   description="Содержит список базовых ссылок до изображений, а также алиасы изображений и их размеры.",
                     *   @OA\RequestBody(
                     *       description="",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestConfig"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseConfig")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getListConfig->handle($options);
                    break;
                case "text":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/text",
                     *   tags={"Mobile Api"},
                     *   description="Получает полный текст новости по ID в двух вариантах – без тегов и с разметкой.",
                     *   @OA\RequestBody(
                     *       description="Получить полный текст новости по ID",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestNewsText"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Полный текст новости",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseNewsText")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getNewsTextById->handle($options);
                    break;
                case "tags":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/tags",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Запрос списка тегов",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestTags"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Список тегов",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseTags")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getListOfTags->handle($options);
                    break;
                case "gallery":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/gallery",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Получение фотографий из галереи для новости",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestGallery"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Фотографии для новости",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseGallery")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $getListOfPhotos->handle($options);
                    break;
                case "push":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/push",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Регистрация токена PUSH-уведомлений",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestPush"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Токен PUSH-уведомлений зарегистрирован",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponsePush")
                     *   ),
                     * )
                     */
                    $resultOfCommand = $push->handle($options);
                    break;
                case "comment":
                    if (!isset($options["action"])) {
                        throw new AnswerOldException($operation,
                            sprintf("Required action for operation \"%s\".", $operation));
                    }

                    if ($options["action"] == "add") {
                        /**
                         * @OA\Post(
                         *   path="/mobile/v1/comment_add",
                         *   tags={"Mobile Api"},
                         *   @OA\RequestBody(
                         *       description="Отправка комментария",
                         *       @OA\JsonContent(ref="#/components/schemas/apiRequestCommentAdd"),
                         *   ),
                         *   @OA\Response(
                         *      response=200,
                         *      description="Комментарий добавлен",
                         *      @OA\JsonContent(ref="#/components/schemas/apiResponseCommentAdd")
                         *   ),
                         * )
                         */
                        $resultOfCommand = $sendComment->handle($options);
                    } elseif ($options["action"] == "get") {
                        /**
                         * @OA\Post(
                         *   path="/mobile/v1/comment_get",
                         *   tags={"Mobile Api"},
                         *   @OA\RequestBody(
                         *       description="Получение комментариев",
                         *       @OA\JsonContent(ref="#/components/schemas/apiRequestCommentGet"),
                         *   ),
                         *   @OA\Response(
                         *      response=200,
                         *      description="Комментарии",
                         *      @OA\JsonContent(ref="#/components/schemas/apiResponseCommentGet")
                         *   ),
                         * )
                         */
                        $resultOfCommand = $getComment->handle($options);
                    } else {
                        throw new AnswerOldException($operation,
                            sprintf("Unknown action \"%s\" for operation \"%s\".", $options["action"], $operation));
                    }

                    break;
                case "types":
                    /**
                     * @OA\Post(
                     *   path="/mobile/v1/types",
                     *   tags={"Mobile Api"},
                     *   @OA\RequestBody(
                     *       description="Запрос типов контента, который комментируется",
                     *       @OA\JsonContent(ref="#/components/schemas/apiRequestTypesForComment"),
                     *   ),
                     *   @OA\Response(
                     *      response=200,
                     *      description="Список типов контента, который комментируется",
                     *      @OA\JsonContent(ref="#/components/schemas/apiResponseTypesForComment")
                     *   ),
                     * )
                     */
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

    private function validateOperation(Request $request, $operation)
    {
        $path = $request->path();

        if (str_contains($path, $operation)) {
            return;
        }

        if ($path == self::PATH_BEGIN) {
            return;
        }

        throw new AnswerOldException($operation,
            sprintf("Operation \"%s\" not contains in path \"%s\"", $operation, $path));
    }
}
