<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
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
