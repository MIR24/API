<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   schema="Episode",
 *   type="object",
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="poster", type="string", format="uri"),
 *     @OA\Property(property="season", type="string", format="uri"),
 *     @OA\Property(property="year", type="integer"),
 *     @OA\Property(property="url", type="string", format="uri", description="Ссылка на видео" ),
 * )
 */
class Episode extends Model
{
    # TODO OA\Property(property="time", type="TODO", description="Время начала передачи и длительность, если нет эпизодов"), #begin duration/end
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
