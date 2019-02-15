<?php

namespace App\Library\Services\Import;


use Illuminate\Support\Facades\DB;

class SmartTvImporter
{
    public function getChannels(): array
    {
        # TODO статический массив возвращать и всё. Или вообще из БД убрать, только в конфиге хранить
        $query = "select id, title as name from tags where type = 8";

        return DB::connection('mir24')->select($query);
    }

    public function saveChannels($channels): self
    {
        $stream = "http://onair.mir24.tv";
        $streamLogo = "http://onair.mir24.tv/images/custom/logo.png";
        $query = "INSERT INTO channels (id, name, stream_shift, stream_live, logo) "
            . "VALUES (?,?,?,?,?) "
            . "ON DUPLICATE KEY "
            . "UPDATE name = VALUES(name), stream_shift = VALUES(stream_shift), stream_live = VALUES(stream_live), logo = VALUES(logo)";

        foreach ($channels as $channel) {
            DB::insert($query, [$channel->id, $channel->name, "", $stream, $streamLogo]);
        }

        return $this;
    }

    public function getBroadcasts(): array
    {
//        $query = "select t1.id, t1.title as name
//            from tags t1
//            left join tag_tag tt on tt.related_tag=t1.id
//            left join tags t2 on t2.id = tt.tag_id
//            where t1.type = 8 and t2.type=4";
//            where t1.type = 4 and t2.type=8";
//        tags.title где type = 4 и который связан с текущим каналом (тег типа 8) через tag_tag

//        return DB::connection('mir24')->select($query);
        return []; # TODO
    }

    public function saveBroadcasts($broadcasts): self
    {
        # TODO
        return $this;
    }
}
