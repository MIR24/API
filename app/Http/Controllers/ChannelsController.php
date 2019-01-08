<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Library\Services\TimeReplacer\StreamUrlReplacer;
use App\Library\Services\TimeReplacer\TimeReplacer;

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
    public function show(TimeReplacer $timeReplacer, StreamUrlReplacer $streamUrlReplacer)
    {
        # TODO массив каналов или какой-то конкретный?
        return response()->json(
            $streamUrlReplacer->replace(
                $timeReplacer->replaceForChannel(
                    Channel::GetForApi()->get()
                )
            )
        );
    }
}
