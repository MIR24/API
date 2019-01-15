<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v2/categories",
     *   summary="Получение списка категорий передач",
     *   tags={"SmartTV"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-4",
     *   @OA\Response(
     *      response=200,
     *      description="Список категорий передач",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CategoryMin"))
     *   ),
     * )
     */
    public function show()
    {
        return response()->json(Category::GetForApi()->get());
    }
}
