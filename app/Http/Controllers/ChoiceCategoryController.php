<?php

namespace App\Http\Controllers;

use App\ChoiceCategory;
use App\Http\Resources\ChoiceCategoryResource;
use Illuminate\Http\Request;

class ChoiceCategoryController extends Controller
{

    /**
     * @OA\Get(
     *   path="/v2/choice",
     *   summary="Получение списка передач в записи для отображения",
     *   tags={"Админка для ios и android"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-5",
     *   @OA\Response(
     *      response=200,
     *      description="Список передач в записи",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChoiceTv")),
     *   ),
     * )
     */
    public function index()
    {
        $choice = ChoiceCategory::all();

        return response()->json($choice);
    }


    /**
     * @OA\Post(
     *     path="/v2/choice",
     *     tags={"Админка для ios и android"},
     *     summary="Создание передачи для отображения",
     *     description="Создание передачи",
     *     @OA\RequestBody(
     *         description="Входные параметры",
     *         @OA\JsonContent(ref="#/components/schemas/ChoiceTv"),
     *     ),
     *     @OA\Response(response="201",
     *         description="Передача создана",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ChoiceTv"),
     *         ),
     *     ),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function store(Request $request)
    {
        $choice  = ChoiceCategory::create($request->all());

        return (new ChoiceCategoryResource( $choice))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *   path="/v2/choice/{id}",
     *   summary="Просмотр передачи",
     *   tags={"Админка для ios и android"},
     *   description="Просмотр ",
     *   @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID передачи"),
     *
     *
     *   @OA\Response(
     *      response=200,
     *      description="Передача",
     *      @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ChoiceTv"),
     *         ),
     *     ),
     * )
     */
    public function show(ChoiceCategory $choice)
    {
        return new ChoiceCategoryResource($choice);
    }

    /**
     * @OA\Put(
     *     path="/v2/choice/{id}",
     *     tags={"Админка для ios и android"},
     *     summary="Редактирование списка передач для отображения",
     *     description="Обновление списка передач для отображения",
     *     @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID передачи "),
     *     @OA\RequestBody(
     *         description="Входные параметры",
     *         @OA\JsonContent(ref="#/components/schemas/ChoiceTv"),
     *     ),
     *     @OA\Response(response="200", description="Передачи обновлены",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ChoiceTv"),
     *         ),
     *     ),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function update(Request $request,ChoiceCategory $choice)
    {
        $choice->update($request->all());

        return new ChoiceCategoryResource($choice);

    }

    /**
     * @OA\Delete(
     *     path="/v2/choice/{id}",
     *    tags={"Админка для ios и android"},
     *    summary="Удаление списка передач для отображения",
     *     description="Архивировать запись",
     *     @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID передачи"),
     *     @OA\Response(response="204", description="Запись архивирована"),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function destroy(ChoiceCategory $choice)
    {
        $choice->delete();

        return response()->json(null, 204);
    }
}
