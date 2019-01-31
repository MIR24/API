<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="CategoryForTv",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 * )
 */
class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    public function scopeGetForTvApi(Builder $query)
    {
        return $query
            ->where("show_smarttv", true)
            ->select([
                'id',
                'name',
            ]);
    }

    public function scopeGetForMobileApi(Builder $query)
    {
        return $query
            ->where("show", true)
            ->select([
                'id',
                'name',
                'url',
                'order'
            ]);
    }
}
