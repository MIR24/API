<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mirreport extends Model
{
    protected $table = 'mirreport';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'title',
        'desc',
        'profile',
        'date',
        'email',
        'filename'
    ];
}
