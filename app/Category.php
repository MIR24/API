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
 *
 * )
 */
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
}
