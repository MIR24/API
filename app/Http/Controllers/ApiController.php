<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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
     *   summary="Унифицированная форма API",
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

    public function index(Request $request)
    {
        $operation = $request->get('request');
        $options = $request->get('options');

        $responseData = [
            'answer' => $operation,
            'status' => 200,
//            'message' => 'TODO',
//            'content' => 'TODO'
        ];
        return response()->json($responseData);
    }
}