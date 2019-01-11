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

    public static function get($countryId, $categoryId)
    {
        $keyCache = "getNewsIdsWithCountryAndCategery";

        if (Cache::has($keyCache)) {
            $news = Cache::get($keyCache);
        } else {
            $news = self::getIdFromDatabase();
            Cache::forever($keyCache, $news);
        }

        $keyArray = $countryId . '_' . $categoryId;
        return array_key_exists($keyArray, $news) ? $news[$keyArray] : [];
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
            if ($item->countryId === null) {
                $item->countryId = env('DEFAULT_COUNTRY');
            }

            $key = $item->countryId . '_' . $item->categoryId;

            if (!isset($newsId[$key])) {
                $newsId[$key] = [];
            }

            $newsId[$key][] = $item->id;
        }

        return $newsId;
    }
}
