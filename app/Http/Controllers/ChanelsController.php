<?php

namespace App\Http\Controllers;

use App\Chanels;
use App\Http\Resources\ChanelsResource;
use Illuminate\Http\Request;

class ChanelsController extends Controller
{

    /**
     * @OA\Get(
     *   path="/v2/chanels",
     *   summary="Получение каналов и передач на неделю для отображения ",
     *   tags={"Админка для ios и android"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-3",
     *   @OA\Response(
     *      response=200,
     *      description="Список категорий передач",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChanelTv")),
     *   ),
     *     security={{"authApi":{}}},
     * )
     */
    public function index()
    {
        return [
            'id' => 1,
            'name' => 'Test',
            'iosLink' => 'https://google.com?hl=ru',
            'androidLink' => 'https://google.com?hl=ru',
            'logo' => 'https://google.com?hl=ru',
        ];
        $chanels = Chanels::all();
        return ChanelsResource::collection($chanels);
    }
    /**
     * @OA\Post(
     *     path="/v2/chanels",
     *     tags={"Админка для ios и android"},
     *     summary="Создание канала или передачи",
     *     description="Создание канала",
     *     @OA\RequestBody(
     *         description="Входные параметры",
     *         @OA\JsonContent(ref="#/components/schemas/ChanelTv"),
     *     ),
     *     @OA\Response(response="201",
     *         description="Канал создан",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ChanelTv"),
     *         ),
     *     ),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function store(Request $request)
    {
        $chanel  = Chanels::create($request->all());

        return (new ChanelsResource($chanel))->response()->setStatusCode(201);
    }


    /**
     * @OA\Get(
     *   path="/v2/chanels/{id}",
     *   summary="Просмотр канала или передачи",
     *   tags={"Админка для ios и android"},
     *   description="Просмотр канала",
     *   @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID канала"),
     *
     *
     *   @OA\Response(
     *      response=200,
     *      description="Канал",
     *      @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ChanelTv"),
     *         ),
     *     ),
     * )
     */
    public function show(Chanels $chanel)
    {
        return new ChanelsResource($chanel);
    }


    /**
     * @OA\Put(
     *     path="/v2/chanels/{id}",
     *     tags={"Админка для ios и android"},
     *     summary="Редактирование списка каналов и  передач для отображения",
     *     description="Обновление списка каналов и  передач для отображения",
     *     @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID канала "),
     *     @OA\RequestBody(
     *         description="Входные параметры",
     *         @OA\JsonContent(ref="#/components/schemas/ChanelTv"),
     *     ),
     *     @OA\Response(response="200", description="Каналы обновлены",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/ChanelTv"),
     *         ),
     *     ),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function update(Request $request,Chanels $chanel)
    {
        $chanel->update($request->all());

        return (new ChanelsResource($chanel))->response()->setStatusCode(200);
    }


    /**
     * @OA\Delete(
     *     path="/v2/chanels/{id}",
     *    tags={"Админка для ios и android"},
     *    summary="Удаление списка каналов и  передач для отображения",
     *     description="Архивировать запись",
     *     @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID канала"),
     *     @OA\Response(response="204", description="Запись архивирована"),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function destroy(Chanels $chanel)
    {
        $chanel->delete();

        return response()->json(null, 204);
    }
}
