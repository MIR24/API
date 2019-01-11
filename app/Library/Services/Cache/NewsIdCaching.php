<?php


namespace App\Library\Services\Cache;


use App\News;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;


class NewsIdCaching
{
    public static function warmup(): void
    {
        Cache::forever("getNewsIdsWithCountryAndCategery", self::getIdFromDatabase());
    }

    public static function get()
    {
        $key = "getNewsIdsWithCountryAndCategery";
        if (Cache::has($key)) {
            $countries = Cache::get($key);
        } else {
            Cache::forever($key, self::getIdFromDatabase());
        }

        return $countries;
    }

    /**
     * Generate table for fast search based on country and category id.
     */
    private static function getIdFromDatabase(): array
    {
        $newsId = [];

        /** @var Collection $news */
        $news = News::GetIdsWithCountryAndCategery()->get();

        foreach ($news as $item) {
            $key = $item->countryId . '_' . $item->categoryId;

            if (!isset($newsId[$key])) {
                $newsId[$key] = [];
            }

            $newsId[$key][] = $item->id;
        }

        return $newsId;
    }
}
