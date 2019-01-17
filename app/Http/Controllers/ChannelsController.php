<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Resources\ChannelResource;
use App\Library\Services\TimeReplacer\StreamUrlReplacer;
use App\Library\Services\TimeReplacer\TimeReplacer;

class ChannelsController extends Controller
{
    /**
     * @OA\Get(
     *   path="/smart/v1/channels",
     *   summary="Получить список телеканалов",
     *   tags={"SmartTV. Channels"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Cписок телеканалов",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChannelCollectionV1")),
     *   ),
     * )
     */
    public function cgetAction()
    {
        # TODO
    }

    /**
     * @OA\Get(
     *   path="/smart/v1/channels/{channelId}",
     *   summary="Получить описание канала",
     *   tags={"SmartTV. Channels"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Parameter(
     *       name="channelId",
     *       in="path",
     *       required=true,
     *       description="Id канала",
     *       @OA\Schema(type="number"),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Описание канала",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/ChannelDetailV1"),
     *   ),
     * )
     */
    public function show($channelId)
    {
        $channel = Channel::find($channelId);
        ChannelResource::withoutWrapping();
        return new ChannelResource($channel);
    }

    /**
     * @OA\Schema(
     *   schema="ProgramV1",
     *   type="object",
     *     @OA\Property(property="title", type="any", description="Название передачи / фильма."),
     *     @OA\Property(property="description", type="any", description="Описание передачи."),
     *     @OA\Property(property="ageRestriction", type="any", description="Возрастное ограничение передачи (0 - без ограничений)."),
     *     @OA\Property(property="time", type="any", description="Время выхода передачи в эфир."),
     *     @OA\Property(property="label", type="any", description="Метка передачи. Например, телешоу, спорт, сериал и т.п."),
     *     @OA\Property(property="links", type="any", description="Массив ссылок на программу и навигация на соседние эфирные сутки."),
     * )
     *
     * @OA\Get(
     *   path="/smart/v1/channels/{channelId}/program",
     *   summary="Получить программу телепередач",
     *   description="Возвращает программу на выбранную дату, если задан параметр date. Без параметра возвращает программу на текущий день. Эфирные сутки - с 6-00 до 6-00 следующего дня.",
     *   tags={"SmartTV. TV Program"},
     *   externalDocs="https://mirtvsmartapi.docs.apiary.io/",
     *   @OA\Response(
     *      response=200,
     *      description="Программа телепередач на выбранную дату",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/ProgramV1"),
     *   ),
     * )
     */
    public function getProgramAction($channelId)
    {
        # TODO параметр date задокументировать
        # TODO
    }

    /**
     * @OA\Get(
     *   path="/smart/v2/channels",
     *   summary="Получение каналов и передач на неделю",
     *   tags={"SmartTV. Channels"},
     *   externalDocs="https://mir24tv.atlassian.net/browse/SSAPI-3",
     *   @OA\Response(
     *      response=200,
     *      description="Каналы со списком передач на неделю",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ChannelV2")),
     *   ),
     * )
     */
    public function indexVersion2(TimeReplacer $timeReplacer, StreamUrlReplacer $streamUrlReplacer) # TODO remove?
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
