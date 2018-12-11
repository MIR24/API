<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
