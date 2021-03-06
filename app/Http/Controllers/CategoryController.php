<?php

namespace App\Http\Controllers;

use App\CategoryTv;

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
    public function show()
    {
        return response()->json(CategoryTv::GetForTvApi()->get());
    }
}
