<?php


namespace App\Library\Services\Cache;


use App\Library\Components\EloquentOptions\NewsOption;
use App\News;
use Illuminate\Support\Facades\Cache;


class NewsCaching
{
    public static function warmup(): void
    {
        $option = self::getOption();

        Cache::forever("lastNews", self::getNews($option));

        $option->setOnlyWithGallery(true);
        Cache::forever("lastNewsWithGallery", self::getNews($option));
        $option->setOnlyWithGallery(false);

        $option->setOnlyVideo(true);
        Cache::forever("lastNewsWithVideo", self::getNews($option));
    }

    public static function getLastNews()
    {
        $key = "lastNews";

        if (Cache::has($key)) {
            $news = Cache::get($key);
        } else {
            $option = self::getOption();
            $news = self::getNews($option);
            Cache::forever($key, $news);
        }

        return $news;
    }

    public static function getLastNewsWithVideo()
    {
        $key = "lastNewsWithVideo";

        if (Cache::has($key)) {
            $news = Cache::get($key);
        } else {
            $option = self::getOption()->setOnlyVideo(true);
            $news = self::getNews($option);
            Cache::forever($key, $news);
        }

        return $news;
    }

    public static function getLastNewsWithGallery()
    {
        $key = "lastNewsWithGallery";

        if (Cache::has($key)) {
            $news = Cache::get($key);
        } else {
            $option = self::getOption()->setOnlyWithGallery(true);
            $news = self::getNews($option);
            Cache::forever($key, $news);
        }

        return $news;
    }

    private static function getOption(): NewsOption
    {
        return (new NewsOption())
            ->setLastNews(true);
    }

    private static function getNews(NewsOption $option)
    {
        $news = News::GetList($option)->get();

        foreach ($news as $newsItem) {
            News::postprocessingOfGetList($newsItem);
        };

        return $news;
    }
}
