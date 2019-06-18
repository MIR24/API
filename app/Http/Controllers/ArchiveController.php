<?php

namespace App\Http\Controllers;

use App\Library\Services\Cache\ArchivesCaching;
use App\Library\Services\TimeReplacer\TimeReplacer;

/**
 * @OA\Get(
 *   path="/api/smart/v1/archives",
 *   summary="Получение списка передач в записи",
 *   tags={"SmartTV"},
 *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-5",
 *   @OA\Response(
 *      response=200,
 *      description="Список передач в записи",
 *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Archive")),
 *   ),
 * )
 */
class ArchiveController extends Controller
{
    public function show(TimeReplacer $replacer)
    {
        return response()->json(ArchivesCaching::get());
    }
}
