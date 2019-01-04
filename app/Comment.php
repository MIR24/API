<?php

namespace App;


use App\Library\Components\EloquentOptions\CommentOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    public const TYPE_ENTITY_NEWS = 0; # TODO In table "types" instead of constant
    public const TYPE_ENTITY_PHOTO = 1;
    public const TYPE_ENTITY_VIDEO = 2;

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

// TODO fill total:
//            query = "SELECT COUNT(id) AS count "
//                    + "FROM   comments "
//                    + "WHERE  entity_id = '" + options.getEntityID() + "'";
//            resultSet = messanger.doQuery(query);
//            if (resultSet.next()) {
//                options.setTotal(resultSet.getInt("count"));
//            }
    }
}
