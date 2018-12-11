<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table='archives';

    public $timestamps=false;

    public function category(){
        return $this->belongsTo('categories');
    }

    public function episodes(){
        return $this->hasMany(Episode::class);
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
                'title',
                'category_id',
                'poster',
                'url',
                'time_begin',
                'time_end'
            ]
        )->with('episodes');

    }

}
