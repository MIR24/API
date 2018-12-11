<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channel';

    public $timestamps = false;

    public function broadcasts()
    {
        return $this->hasMany(Broadcasts::class);

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
                'name',
                'stream_shift',
                'stream_live',
                'logo'
            ]
        )->with('broadcasts');

    }
}
