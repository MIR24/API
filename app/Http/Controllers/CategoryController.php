<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryTv;
use App\Chanels;
use App\Http\Resources\CategoriesResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/smart/v1/categories",
     *   summary="Получение списка категорий передач",
     *   tags={"SmartTV"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-4",
     *   @OA\Response(
     *      response=200,
     *      description="Список категорий передач",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CategoryForTv"))
     *   ),
     * )
     */
    public function show_tv()
    {
        return response()->json(CategoryTv::GetForTvApi()->get());
    }

    /**
     * @OA\Get(
     *   path="/v2/categories",
     *   summary="Получение списка категорий передач для отображения ",
     *   tags={"Админка для ios и android"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-4",
     *   @OA\Response(
     *      response=200,
     *      description="Список категорий передач",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CategoryForTv"))
     *   ),
     * )
     */
    public function index()
    {
        return CategoriesResource::collection(Category::all());
    }

    /**
     * @OA\Get(
     *   path="/v2/categories/{id}",
     *   summary="Просмотр категории",
     *   tags={"Админка для ios и android"},
     *   description="Просмотр категории",
     *   @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID категории"),
     *
     *
     *   @OA\Response(
     *      response=200,
     *      description="Категория",
     *      @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryForTv"),
     *         ),
     *     ),
     * )
     */
    public function show(Category $category)
    {
        return new CategoriesResource($category);
    }

    /**
     * @OA\Post(
     *     path="/v2/categories",
     *     tags={"Админка для ios и android"},
     *     summary="Создание категории",
     *     description="Создание категории",
     *     @OA\RequestBody(
     *         description="Входные параметры",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryTv"),
     *     ),
     *     @OA\Response(response="201",
     *         description="Категория создана",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryTv"),
     *         ),
     *     ),
     *     security={{"authApi":{}}},
     * )
     *
     */

    public function store(Request $request)
    {
        $categories  = Category::create($request->all());

        return (new CategoriesResource($categories))->response()->setStatusCode(201);
    }

    /**
     * @OA\Put(
     *     path="/v2/categories/{id}",
     *     tags={"Админка для ios и android"},
     *     summary="Редактирование списка категорий передач для отображения",
     *     description="Обновление списка категорий передач для отображения",
     *     @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID категории "),
     *     @OA\RequestBody(
     *         description="Входные параметры",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryTv"),
     *     ),
     *     @OA\Response(response="200", description="Категории обновлены",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", ref="#/components/schemas/CategoryForTv"),
     *         ),
     *     ),
     *     security={{"authApi":{}}},
     * )
     *
     */
    public function update(Request $request,Category $category)
    {
        $category->update($request->all());

        return (new CategoriesResource($category))->response()->setStatusCode(200);
    }


    /**
     * @OA\Delete(
     *     path="/v2/categories/{id}",
     *    tags={"Админка для ios и android"},
     *    summary="Удаление списка категорий передач для отображения",
     *     description="Архивировать запись",
     *     @OA\Parameter(name="id", in="path", @OA\Schema(type="integer"), description="ID категории"),
     *     @OA\Response(response="204", description="Запись архивирована"),
     *     security={{"authApi":{}}},
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }

}
