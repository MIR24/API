<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="CategoryTv",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="url", type="string"),
 *      @OA\Property(property="order", type="integer"),
 *       @OA\Property(property="show", type="boolean"),
 * )
 */
class Category extends Model
{
    protected $fillable = ['id','name','url', 'order', 'show'];

    protected $table = 'categories';

    public $timestamps = false;

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
