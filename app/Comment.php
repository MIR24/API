<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    public const TYPE_ENTITY_NEWS = 0; # TODO In table "types" instead of constant
    public const TYPE_ENTITY_PHOTO = 1;
    public const TYPE_ENTITY_VIDEO = 2;

    protected $table = 'comments';

    protected $fillable = ["name", "profile", "email", "entity_id", "text", "type_id"];

    public $timestamps = false;
}
//    /**
//     * Get user comment based on filters passed with CommentOptions instance.
//     *
//     * @param options
//     * @return array of Comment objects or empty array if error occurs
//     */
//    public ArrayList<Comment> getComments(CommentOptions options) {
//        DBMessanger messanger = new DBMessanger("m24api");
//        ArrayList<Comment> comments = new ArrayList<>();
//        query = "SELECT   id, name, profile, time, text, email "
//                + "FROM     comments "
//                + "WHERE    entity_id = '" + options.getEntityID() + "' "
//                + "AND      type_id = " + options.getType() + " "
//                + "ORDER BY id DESC "
//                + "LIMIT " + (options.getPage() - 1) * options.getLimit() + ", "
//                + options.getLimit();
//        try {
//            ResultSet resultSet = messanger.doQuery(query);
//            while (resultSet.next()) {
//                Comment comment = new Comment();
//                comment.setName(resultSet.getString("name"));
//                comment.setText(resultSet.getString("text"));
//                comment.setTime(resultSet.getTimestamp("time"));
//                comment.setProfile(resultSet.getString("profile"));
//                comment.setId(resultSet.getInt("id"));
//                comments.add(comment);
//            }
//            query = "SELECT COUNT(id) AS count "
//                    + "FROM   comments "
//                    + "WHERE  entity_id = '" + options.getEntityID() + "'";
//            resultSet = messanger.doQuery(query);
//            if (resultSet.next()) {
//                options.setTotal(resultSet.getInt("count"));
//            }
//        } catch (SQLException sqlex) {
//            logger.error("Can't get comments for news id " + options.getEntityID() + ":"
//                    + sqlex.getMessage());
//        }
//        return comments;
//    }
//}