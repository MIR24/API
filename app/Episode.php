<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="time",
 *   type="object",
 *   description="Время начала передачи и длительность",
 *   @OA\Property(property="begin", type="string"),
 *   @OA\Property(property="end", type="string"),
 * )
 */

/**
 * @OA\Schema(
 *   schema="Episode",
 *   type="object",
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="poster", type="string", format="uri"),
 *     @OA\Property(property="season", type="string", format="uri"),
 *     @OA\Property(property="year", type="integer", maximum=9999),
 *     @OA\Property(property="time", type="object", ref="#/components/schemas/time"),
 *     @OA\Property(property="url", type="string", format="uri", description="Ссылка на видео" ),
 * )
 *
 * @OA\Schema(
 *   schema="EpisodeDetailV1",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="any", description="Название эпизода."),
 *     @OA\Property(property="description", type="any", description="Короткое описание."),
 *     @OA\Property(property="text", type="any", description="Подробное описание эпизода."),
 *     @OA\Property(property="posterURL", type="any", description="Главное изображение для отображения в списке эпизодов."),
 *     @OA\Property(property="publishDate", type="any", description="Дата выхода эпизода в формате ISO8601."),
 *     @OA\Property(property="ageRestriction", type="any", description="Возрастное ограничение эпизода."),
 *     @OA\Property(property="video", type="any", description="Ссылка на видео и его длительность в секундах (объект с полями link и duration)."),
 *     @OA\Property(property="images", type="any", description="Массив ссылок на изображения доступные для данного эпизода (одно изображение разного размера)."),
 *     @OA\Property(property="imagesCopyright", type="any", description="Копирайт для изображения из массива images."),
 *     @OA\Property(property="links", type="any", description="Массив ссылок на секцию, список передач или эпизодов."),
 * )
 */
class Episode extends Model
{
    protected $table = 'episodes';

    public $timestamps = false;

    protected $hidden = [
        'id',
        'archive_id'
    ];

    public function archive()
    {
        return $this->belongsTo(Archive::class);
    }
}
