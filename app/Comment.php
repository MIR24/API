<?php

namespace App;


use App\Library\Components\EloquentOptions\CommentOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ["name", "profile", "email", "entity_id", "text", "type_id"];

    public $timestamps = false;

    public function scopeGetComments(Builder $query, CommentOptions $options): Builder
    {
        return $query->select("id", "name", "profile", "time", "text", "email")
            ->where("entity_id", $options->getEntityID())
            ->where("type_id", $options->getType())
            ->orderBy("id", "DESC")
            ->limit($options->getLimit())
            ->offset($options->getCalculatedOffset());
    }

    public function scopeGetTotalCommentsForEntity(Builder $query, CommentOptions $options): Builder
    {
        return $query
            ->where("entity_id", $options->getEntityID())
            ->where("type_id", $options->getType());
    }
}
