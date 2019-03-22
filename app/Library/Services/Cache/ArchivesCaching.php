<?php


namespace App\Library\Services\Cache;


use App\Archive;
use App\Library\Services\TimeReplacer\TimeReplacer;
use Illuminate\Support\Facades\Cache;


class ArchivesCaching
{
    public static function warmup(): void
    {
        self::get(true);
    }

    public static function get($forceCache = false)
    {
        $key = "smart.v1.archives";

        if (Cache::has($key) && !$forceCache) {
            $achives = Cache::get($key);
        } else {
            $achives = self::getFromDatabase();
            Cache::forever($key, $achives);
        }

        return $achives;
    }

    private static function getFromDatabase()
    {
        $timeReplacer = \App::make(TimeReplacer::class);

        return $timeReplacer->replaceForArchive(Archive::GetForApi()->get());
    }
}
