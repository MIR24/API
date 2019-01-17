<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BroadcastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @OA\Schema(
     *   schema="BroadcastDetailV1",
     *   type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="any"),
     *     @OA\Property(property="description", type="any", description="описание передачи."),
     *     @OA\Property(property="posterURL", type="any", description="главное изображение для отображения в списке передач."),
     *     @OA\Property(property="ageRestriction", type="any", description="возрастное ограничение шоу (0 - без ограничений)."),
     *     @OA\Property(property="images", type="any", description="Массив ссылок на доступные изображения для передачи с указанием размеров и параметра rel для каждого."),
     *     @OA\Property(property="links", type="any", description="Массив ссылок на передачу и эпизоды передачи."),
     * )
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->title,
            "description" => $this->subtitle,
# TODO            "posterURL" => "main.jpg",
            "ageRestriction" => $this->age,
// TODO            "images" => [
//                [
//                    "height" => 271,
//                    "link" => "main.jpg",
//                    "rel" => "main",
//                    "width" => 408
//                ],
//                [
//                    "height" => 159,
//                    "link" => "second.jpg",
//                    "rel" => "second",
//                    "width" => 300
//                ],
//                [
//                    "height" => 240,
//                    "link" => "third.jpg",
//                    "rel" => "third",
//                    "width" => 641
//                ]
//            ],

// TODO            "links" => [
//                [
//                    "link" => route('broadcasts.show', ['broadcastId' => $this->id]),
//                    "rel" => "self"
//                ],
//                [
//                    "link" => route('broadcast.episodes.index', ['broadcastId' => $this->id]),
//                    "rel" => "episodes"
//                ]
//            ]
        ];
    }
}
