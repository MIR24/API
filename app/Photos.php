<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    protected $table = 'photos';

    public function scopeGetList(Builder $query, int  $id): Builder{
       return $query->select(['image_id  as id'])->where('news_id','=',$id);

    }
}
