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
class CategoryTv extends Model
{
    protected $table = 'categories_tv';

    public $timestamps = false;

    protected $fillable = ['id', 'name'];

    public function scopeGetForTvApi(Builder $query)
    {
        return $query
            ->where("show_smarttv", true)
            ->select([
                'id',
                'name',
            ]);
    }
}
