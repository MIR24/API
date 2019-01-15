<?php

namespace App\Http\Controllers;


class BroadcastController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v1/sections/{sectionId}/broadcasts",
     *   summary="Получить список передач для секции",
     *   tags={"SmartTV. Broadcasts"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Cписок передач для секции",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/BroadcastCollectionV1")),
     *   ),
     * )
     */
    public function cgetAction($sectionId)
    {
        # TODO
    }

    /**
     * @OA\Get(
     *   path="/smart/v1/broadcasts/{broadcastId}",
     *   summary="Получить описание передачи",
     *   tags={"SmartTV. Broadcasts"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Описание передачи",
     *      @OA\JsonContent(type="object", @OA\Items(ref="#/components/schemas/BroadcastDetailV1")),
     *   ),
     * )
     */
    public function getAction($broadcastId)
    {
        # TODO
    }
}
