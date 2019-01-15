<?php

namespace App\Http\Controllers;


class EpisodeController extends Controller
{
    # TODO Episodes for section
    # TODO Episodes for broadcast

    /**
     * @OA\Tag(
     *     name="SmartTV. Episodes",
     *     description="Эпизод - конечный ресурс со ссылкой на видеоконтент с описанием. Может быть привязан к секции или передаче. Один эпизод может принадлежать к нескольким секциям, но только к одной передаче",
     * )
     *
     * @OA\Get(
     *   path="/smart/v1/episodes/{episodeId}",
     *   summary="Получить подробное описание эпизода",
     *   tags={"SmartTV. Episodes"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Подробное описание эпизода",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/EpisodeDetailV1"),
     *   ),
     * )
     */
    public function getAction($episodeId)
    {
        # TODO
    }

    /**
     * @OA\Get(
     *   path="/smart/v2/episodes/{episodeId}",
     *   summary="Получить подробное описание эпизода",
     *   tags={"SmartTV. Episodes"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Подробное описание эпизода",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/Episode"),
     *   ),
     * )
     */
    public function getV2Action($episodeId)
    {
        # TODO
    }
}
