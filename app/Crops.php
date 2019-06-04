<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Crops extends Model
{
    protected $fillable = ['news_id', 'src', 'itemType'];

    public function scopeGetImage(Builder $query, $imageID, $type)
    {
        $query
            ->select('crops.src')
            ->join('news', 'crops.news_id', '=', 'news.id')
            ->where('crops.itemType', $type)
            ->orWhere('crops.itemType', 'detail_crop')
            ->where('news.imageID', $imageID);


        return $query;
    }
}
