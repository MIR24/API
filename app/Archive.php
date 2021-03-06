<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="Archive",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="category", type="integer",
 *       description="Id категории, в которой расположена передача",
 *     ),
 *     @OA\Property(property="poster", type="string", format="uri",
 *       description="Главное изображение для отображения в списке",
 *     ),
 *     @OA\Property(property="episodes", type="array",
 *       description="Массив эпизодов, если есть",
 *       @OA\Items(ref="#/components/schemas/Episode")
 *     ),
 *     @OA\Property(property="url", type="string", format="uri",
 *       description="Ссылка на видео, если нет эпизодов",
 *     ),
 *     @OA\Property(property="time", type="object", ref="#/components/schemas/time",
 *       description="Время начала передачи и длительность, если нет эпизодов"
 *     ),
 *   )
 */
class Archive extends Model
{
    protected $table = 'archives';

    protected $fillable = ['id', 'title', 'category_id', 'poster', 'url', 'time_begin', 'time_end'];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('categories');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class)->orderBy('time_begin','desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeGetForApi($query)
    {
        return $query->select(
            [
                'id',
                'title',
                'category_id',
                'poster',
                'url',
                'time_begin',
                'time_end'
            ]
        )
            ->with('episodes')
            ->orderBy('id','desc');

    }

}
