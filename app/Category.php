<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="CategoryMin",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 * )
 * @OA\Schema(
 *   schema="CategoryMax",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="url", type="string"),
 *     @OA\Property(property="order", type="integer"),
 * )
 * @OA\Schema(
 *   schema="SectionDetail",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="any"),
 *     @OA\Property(property="links", type="any", description="Массив ссылок на секцию, список передач или эпизодов."),
 *     @OA\Property(property="rootSection", type="any", description="Флаг, который указывает, что секция содержит эпизоды, а не передачи, т.е. является корневой. От него зависит, какая ссылка будет передана массиве links - episodes или broadcasts."),
 * )
 * @OA\Schema(
 *   schema="SectionCollection",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="any"),
 *     @OA\Property(property="rootSection", type="any", description="Флаг, который указывает, что секция содержит эпизоды, а не передачи, т.е. является корневой. От него зависит, какая ссылка будет передана массиве links - episodes или broadcasts."),
 * )
 */
class Category extends Model
{
    # TODO Секции, категории новостей и категории передач - это разное или одно и то же?
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
