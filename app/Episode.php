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
 */
class Episode extends Model
{
    protected $table = 'episodes';

    protected $fillable = ['id', 'title', 'poster', 'season', 'year', 'time_begin', 'time_end', 'url', 'archive_id'];

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
