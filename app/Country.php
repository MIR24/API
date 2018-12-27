<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    public function scopeGetForApi(Builder $query)
    {
        return $query
            ->where('published', 'true')
            ->select(['id', 'name']);
    }
}
