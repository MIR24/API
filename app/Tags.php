<?php

namespace App;

use App\Exceptions\InvalidClientOldException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tags extends Model
{

    protected $table = 'tags';

    public function scopeGetListByTagsID(Builder $query, array $ids): Builder
    {
        return $query->select()->whereIn('id', $ids);

    }

    public function scopeGetListTags(Builder $query, string $sort_type, int $page, int $limit = 100, string $date_interval = 'now - 1 month'): Builder
    {

        if ($sort_type == 'actual')
            return $query->select(['tags.id', 'tags.name', DB::raw('COUNT(tag_id) count')])
                ->leftJoin('news_tags', 'news_tags.tag_id', '=', 'tags.id')
                ->leftJoin('news', 'news.id', '=', 'news_id')
                ->where("news.date", ">", new \DateTime($date_interval))
                ->groupBy(['tags.id'])
                ->orderBy('count', 'desc')
                ->limit($limit)
                ->offset($limit * ($page - 1));

        if ($sort_type == 'top')
            return $query->select([DB::raw('DISTINCT name'), 'tag_id', DB::raw('COUNT(tag_id) count')])
                ->fromRaw('( SELECT n.id, nt.tag_id, t.name FROM news n ' .
                    'LEFT JOIN news_tags nt ON nt.news_id = n.id ' .
                    'LEFT JOIN tags t ON t.id = nt.tag_id ' .
                    'ORDER BY id DESC LIMIT 200 ) as a ')
                ->groupBy('tag_id')
                ->orderBy('tag_id', 'desc');

        throw new InvalidClientOldException('tags', 'Invalid type sort ' . $sort_type);
    }
}
