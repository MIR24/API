<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="Category",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 * )
 */
# TODO Add 'url' and 'order' in documentation?
class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

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
            ]
        );
    }

    public function scopeGetForOldApi(Builder $query)
    {
        return $query->select(
            [
                'id',
                'name',
                'url',
                'order'
            ]
        );
    }
}
