<?php

namespace App\Http\Controllers;

use App\Archive;

class ArchiveController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v1/archives",
     *   summary="Получение списка передач в записи",
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-5",
     *   @OA\Response(
     *      response=200,
     *      description="Список передач в записи",
     *      @OA\JsonContent(ref="#/components/schemas/Archive"),
     *   ),
     * )
     */
    public function show()
    {
        return response()->json(Archive::GetForApi()->get());
    }
}
