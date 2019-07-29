<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chanels extends Model
{
    protected $fillable=['id','name','iosLink','androidLink','logo'];


//сокрытие полей
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
