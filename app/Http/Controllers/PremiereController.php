<?php

namespace App\Http\Controllers;

use App\Library\Services\Import\MIRTVImporter;

/**
 * /**
 * @OA\Schema(
 *   schema="Premiere",
 *   type="object",
 *     @OA\Property(property="premiere_id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="time", type="string"),
 *   )
 *
 * @OA\Get(
 *   path="/v2/premiere",
 *   summary="Получение списка примьер на телеканале миртв",
 *   tags={"MirTV"},
 *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-5",
 *   @OA\Response(
 *      response=200,
 *      description="Список примьер",
 *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Premiere")),
 *   ),
 * )
 */
class PremiereController extends Controller
{
    public function index(MIRTVImporter $importer)
    {
        return response()->json($importer->getPremiere());
    }
}
