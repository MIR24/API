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
 *   schema="ChannelDetailV1",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="streamURL", type="string", format="uri", description="Ссылка на онлайн-вещание канала"),
 *     @OA\Property(property="logoURL", type="string", format="uri", description="Ссылка на логотип канала в формате PNG"),
 *     @OA\Property(property="links", type="object",
 *         description="Массив ссылок на сам ресурc, список секций (пунктов меню) для телеканала и телепрограмму на сегодняшний день.",
 *     ),
 * )
 *
 * @OA\Schema(
 *   schema="ChannelCollectionV1",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="streamURL", type="string", format="uri", description="Ссылка на онлайн-вещание канала"),
 *     @OA\Property(property="logoURL", type="string", format="uri", description="Ссылка на логотип канала в формате PNG"),
 * )
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
