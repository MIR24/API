<?php

namespace App\Http\Controllers;


use App\Library\Services\Cache\ChannelsCaching;


class ChannelsController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v1/channels",
     *   summary="Получение каналов и передач на неделю",
     *   tags={"SmartTV"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-3",
     *   @OA\Response(
     *      response=200,
     *      description="Каналы со списком передач на неделю",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Channel")),
     *   ),
     * )
     */
    public function show()
    {
        return response()->json(ChannelsCaching::getWithBroadcasts());
    }
}
