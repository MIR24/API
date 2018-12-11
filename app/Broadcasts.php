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
 *     @OA\Property(property="categoryId", type="integer"),
 * )
 */
class Broadcasts extends Model
{
# TODO OA\Property(property="time", type="TODO", description="Время начала передачи и длительность"), #begin duration/end
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
