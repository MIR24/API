<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   schema="Broadcasts",
 *   type="object",
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="subtitle", type="string"),
 *     @OA\Property(property="age", type="integer", description="Возрастное ограничение передачи (0 - без ограничений)."),
 *     @OA\Property(property="dayOfWeek", type="string", description="День недели"),
 *     @OA\Property(property="time", type="object", ref="#/components/schemas/time"),
 *     @OA\Property(property="categoryId", type="integer"),
 * )
 * @OA\Schema(
 *   schema="BroadcastDetailV1",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="any"),
 *     @OA\Property(property="description", type="any", description="описание передачи."),
 *     @OA\Property(property="posterURL", type="any", description="главное изображение для отображения в списке передач."),
 *     @OA\Property(property="ageRestriction", type="any", description="возрастное ограничение шоу (0 - без ограничений)."),
 *     @OA\Property(property="images", type="any", description="Массив ссылок на доступные изображения для передачи с указанием размеров и параметра rel для каждого."),
 *     @OA\Property(property="links", type="any", description="Массив ссылок на передачу и эпизоды передачи."),
 * )
 * @OA\Schema(
 *   schema="BroadcastCollectionV1",
 *   type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="any"),
 *     @OA\Property(property="posterURL", type="any", description="главное изображение для отображения в списке передач."),
 *     @OA\Property(property="ageRestriction", type="any", description="возрастное ограничение шоу (0 - без ограничений)."),
 * )
 */
class Broadcasts extends Model
{
    protected $table='broadcasts';

    protected $hidden=[
            'id',
            'channel_id'
    ];

    public $timestamps=false;

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function channel(){
        return $this->belongsTo('App\Channel');
    }

}
