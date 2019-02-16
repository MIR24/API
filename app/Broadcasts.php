<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   schema="Broadcasts",
 *   type="object",
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="subtitle", type="string"),
 *     @OA\Property(property="ageRestriction", type="integer", description="Возрастное ограничение передачи (0 - без ограничений)."),
 *     @OA\Property(property="dayOfWeek", type="string", description="День недели"),
 *     @OA\Property(property="time", type="object", ref="#/components/schemas/time"),
 *     @OA\Property(property="categoryId", type="integer"),
 * )
 */
class Broadcasts extends Model
{
    protected $table = 'broadcasts';

    protected $fillable = ['id', 'title', 'subtitle', 'age_restriction', 'day_of_week', 'time_begin', 'time_end', 'category_id', 'channel_id'];

    protected $hidden = [
        'id',
        'channel_id'
    ];

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

}
