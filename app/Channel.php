<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
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
 *   schema="Channel",
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
class Channel extends Model
{
    protected $table = 'channels';

    public $timestamps = false;

    protected $fillable=['id','name','stream_shift','stream_live','logo'];

    public function broadcasts()
    {
        return $this->hasMany(Broadcasts::class);

    }

    public function week_broadcasts()
    {
        return $this->hasMany(Broadcasts::class)
            ->orderBy('time_begin')
            ->whereBetween('time_begin',[
               date('Y-m-d 00:00:00',strtotime('monday this week')),
               date('Y-m-d 00:00:00',strtotime('monday next week')),
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeGetForApiWithWeekBroadcasts($query)
    {
        return $query->select(
            [
                'id',
                'name',
                'stream_shift',
                'stream_live',
                'logo'
            ]
        )->with('week_broadcasts');

    }
}
