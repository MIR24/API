<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="Channel",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="stream_shift", type="string", format="uri",
 *       description="Ссылка на вещание телеканала в записи (если есть)"),
 *     @OA\Property(property="stream_live", type="string", format="uri",
 *       description="Ссылка на вещание прямого эфира"),
 *     @OA\Property(property="logo", type="string", format="uri"),
 *     @OA\Property(property="broadcasts", type="array", description="Массив передач на неделю",
 *        @OA\Items(ref="#/components/schemas/Broadcasts")
 *     ),
 *   )
 */
class Channel extends Model
{
    protected $table = 'channel';

    public $timestamps = false;

    public function broadcasts()
    {
        return $this->hasMany(Broadcasts::class);

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
                'name',
                'stream_shift',
                'stream_live',
                'logo'
            ]
        )->with('broadcasts');

    }
}
