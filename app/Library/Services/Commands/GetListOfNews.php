<?php

namespace App\Library\Services\Commands;


use App\ActualNews;
use App\Http\Resources\NewsItemResource;
use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Services\Cache\NewsCaching;
use App\Library\Services\Cache\NewsIdCaching;
use App\Library\Services\ResultOfCommand;
use App\News;


class GetListOfNews implements CommandInterface
{
    private const OPERATION = "newslist";

    public function handle(array $options): ResultOfCommand
    {
        $newsOption = (new NewsOption())->initFromArray($options);

        $news = $this->selectFromCache($newsOption);
        if (count($news) == 0) {
            $news = $this->selectFromDb($newsOption);
        }

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent(NewsItemResource::collection($news))
            ->setMessage(sprintf("Total of %d news parsed.", count($news)))
            ->setStatus(200);
    }

    private function selectFromCache(NewsOption $options)
    {
        $news = [];

        if (!$options->isLastNews()) {
            return $news;
        }

        if ($options->getPage() == 1
            && $options->getLimit() == $options::LIMIT_DEFAULT
            && $options->getCountryID() === null
            && $options->getCategory() === null
        ) {
            if ($options->isOnlyVideo()) {
                $news = NewsCaching::getLastNewsWithVideo();
            } elseif ($options->isOnlyWithGallery()) {
                $news = NewsCaching::getLastNewsWithGallery();
            } else {
                $news = NewsCaching::getLastNews();
            }
        }

        if (count($news) === 0
            && $options->getCountryID() !== null
            && $options->getCategory() !== null
            && !$options->isOnlyVideo()
            && !$options->isOnlyWithGallery()
        ) {
            $newsIds = NewsIdCaching::get($options->getCountryID(), $options->getCategory());

            $preSearch = array_slice($newsIds, $options->getCalculatedOffset(), $options->getLimit());
            if (count($preSearch)) {
                $options->setPreSearch($preSearch);
                $news = $this->selectFromDb($options);
            }
        }

        return $news;
    }

    private function selectFromDb(NewsOption $newsOption)
    {
        $news = News::getPostprocessedList($newsOption);

        // Если в актуальных новостях не хватает новостей, то дополнить результат из обычных новостей
        if ($newsOption->isActual() && $newsOption->getPage() == 1 && count($news) < ActualNews::PROMO_NEWS_COUNT) {
            $newsOption->setActual(false);

            $ignoreId = [];
            foreach ($news as $newsItem) {
                $ignoreId[] = $newsItem->id;
            }
            $newsOption->setIgnoreId($ignoreId);
            $newsOption->setLimit(ActualNews::PROMO_NEWS_COUNT - count($news));
            $news->merge(News::getPostprocessedList($newsOption));
        }

        return $news;
    }
}
