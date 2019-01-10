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

    public static function get() # TODO
    {
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
