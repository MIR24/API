<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @OA\Schema(
     *   schema="stream",
     *   type="object",
     *     @OA\Property(property="shift", type="string", format="uri",
     *       description="Ссылка на вещание телеканала в записи (если есть)"),
     *     @OA\Property(property="live", type="string", format="uri",
     *       description="Ссылка на вещание прямого эфира"),
     *   )
     *
     * @OA\Schema(
     *   schema="ChannelV2",
     *   type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="stream", type="object", ref="#/components/schemas/stream"),
     *     @OA\Property(property="logo", type="string", format="uri"),
     *     @OA\Property(property="broadcasts", type="array", description="Массив передач на неделю",
     *        @OA\Items(ref="#/components/schemas/Broadcasts")
     *     ),
     *   )
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "stream" => [
                "shift" => $this->stream_shift,
                "live" => $this->stream_live
            ],
            "logo" => $this->logo,
// TODO            "broadcasts" => [
//                [
//                    "title" => "string",
//                    "subtitle" => "string",
//                    "age" => 0,
//                    "dayOfWeek" => "string",
//                    "time" => [
//                        "begin" => "string",
//                        "end" => "string"
//                    ],
//                    "categoryId" => 0
//                ]
//            ]
        ];
    }
}
