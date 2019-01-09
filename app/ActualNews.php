<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class ActualNews extends Model
{
    public const PROMO_NEWS_COUNT = 5;

    protected $table = 'actual_news';
}
