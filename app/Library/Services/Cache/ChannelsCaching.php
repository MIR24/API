<?php


namespace App\Library\Services\Cache;


use App\Channel;
use App\Library\Services\TimeReplacer\StreamUrlReplacer;
use App\Library\Services\TimeReplacer\TimeReplacer;
use Illuminate\Support\Facades\Cache;


class ChannelsCaching
{
    public static function warmup(): void
    {
        $channels = self::getWithBroadcastsFromDatabase();
        Cache::forever("channelsWithBroadcasts", $channels);
    }

    //TODO create new cache logic for 1 week???
    public static function getWithBroadcasts()
    {
//        if (Cache::has("channelsWithBroadcasts")) {
//            $channels = Cache::get("channelsWithBroadcasts");
//        } else {
            $channels = self::getWithBroadcastsFromDatabase();
//            Cache::forever("channelsWithBroadcasts", $channels);
//        }
//
        return $channels;
    }

    private static function getWithBroadcastsFromDatabase()
    {
        $streamUrlReplacer = \App::make(StreamUrlReplacer::class);
        $timeReplacer = \App::make(TimeReplacer::class);

        $channels = $streamUrlReplacer->replace(
            $timeReplacer->replaceForChannel(
                Channel::GetForApi()->get()
            )
        );

        return $channels;
    }
}
