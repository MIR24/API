<?php

namespace App\Http\Controllers;

use App\Channel;

class ChannelsController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v1/channels",
     *   summary="Получение канала и передач на неделю",
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-3",
     *   @OA\Response(
     *      response=200,
     *      description="Канал со списком передач на неделю",
     *      @OA\JsonContent(ref="#/components/schemas/Channel"),
     *   ),
     * )
     */
    public function show()
    {
        # TODO stream_shift и stream_live преобразовать в: stream:{ shift:"x", live:"y" }
        return response()->json(Channel::GetForApi()->get());
    }
}
