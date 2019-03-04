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
        self::getWithWeekBroadcasts(true);
    }

    public static function getWithWeekBroadcasts($forceCache = false)
    {
        $date = date('Ymd', strtotime('monday this week'));
        $key = "channelsWithWeekBroadcasts" . $date;

        if (Cache::has($key) && !$forceCache) {
            $channels = Cache::get($key);
        } else {
            $channels = self::getWithBroadcastsFromDatabase();
            Cache::put($key, $channels, new \DateTime('1 week'));
        }

        return $channels;
    }

    private static function getWithBroadcastsFromDatabase()
    {
        $streamUrlReplacer = \App::make(StreamUrlReplacer::class);
        $timeReplacer = \App::make(TimeReplacer::class);

        $channels = $streamUrlReplacer->replace(
            $timeReplacer->replaceForChannel(
                Channel::GetForApiWithWeekBroadcasts()->get()
            )
        );

        return $channels;
    }
}
