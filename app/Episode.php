<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{

    protected $table='episodes';

    public $timestamps=false;

    protected $hidden=[
        'id',
        'archive_id'
    ];

    public function archive(){
        return $this->belongsTo(Archive::class);
    }
}
