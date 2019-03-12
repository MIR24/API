<?php

namespace App;


use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Components\NewsTextConverter;
use App\Library\Services\Transliterator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class News extends Model
{
    protected $table = 'news';

    public function scopeGetNewsText(Builder $query, $newsId): Builder
    {
        return $query
            ->select(['title', 'text', 'textSrc', 'hasGallery', 'c.url'])
            ->leftJoin("categories as c", "c.id", "=", "news.categoryID")
            ->where("news.id", $newsId);
    }

    public function scopeGetList(Builder $query, NewsOption $options): Builder
    {
        $fieldsForSelect = "news.id, date, shortText, shortTextSrc, text, textSrc, title, imageID, "
            . "       categoryID, serieID, videoID, copyright, copyrightSrc, "
            . "       rushHourNews, topListNews, hasGallery, videoDuration, (SELECT GROUP_CONCAT("
            . "       tag_id SEPARATOR ',') FROM news_tags WHERE news_id = news.id) AS tags, "
            . "       (SELECT GROUP_CONCAT(country_id SEPARATOR ',') FROM news_country "
            . "       WHERE news_id = news.id) AS country ";

        $query->select(DB::raw($fieldsForSelect));

        $preSearch = $options->getPreSearch();
        if ($preSearch != null && count($preSearch)) {
            $query->whereIn("news.id", $preSearch)
                ->orderByRaw("news.categoryID, news.id DESC");
        } else {
            $this->optionToWhere($query, $options);
        }

        return $query;
    }

    private function optionToWhere(Builder $query, NewsOption $options): void
    {
        $query->where("published", true);

        if ($options->getPage() === null) {
            $options->setPage(1);
        }

        if ($options->getLimit() !== null) {
            $query->limit($options->getLimit())
                ->offset($options->getCalculatedOffset());
        }

        if ($options->getNewsID() !== null) {
            $query->where(['news.id' => $options->getNewsID()]);
        }

        if ($options->isActual()) {
            $query->rightJoin("actual_news as an", "news.id", "=", "an.news_id")
                ->orderBy("an.id", "ASC");
        } else {
            $query->orderBy("news.id", "DESC");
        }

        if ($options->getCategory() === null) {
            $query->leftJoin("categories as c", "c.id", "=", "news.categoryID")
                ->where("c.show", true);
        } else {
            $query->where("categoryID", $options->getCategory());
        }

        if ($options->isOnlyVideo()) {
            $query->where("videoID", "!=", 0);
        }

        if ($options->isOnlyWithGallery()) {
            $query->where("hasGallery", 1);
        }

        if ($options->getTags() !== null && count($options->getTags())) {

            $query->rightJoin("news_tags as nt", "news.id", "=", "nt.news_id")
                ->whereIn("nt.tag_id",  $options->getTags());
        }
        if ($options->getCountryID() !== null ) {

            $query->rightJoin("news_country as nc", "news.id", "=", "nc.news_id")
                ->where("nc.country_id", $options->getCountryID());
        }
        if ($options->getIgnoreId() !== null && count($options->getIgnoreId())>0 ) {

            $query->whereIn('news.id',$options->getIgnoreId(),'and',true );
        }

    }

    public static function getPostprocessedList(NewsOption $option)
    {
        $news = self::GetList($option)->get();

        foreach ($news as $newsItem) {
            self::postprocessingOfGetList($newsItem);
        };

        return $news;
    }

    public static function postprocessingOfGetList(News $newsItem)
    {
        if ($newsItem->tags !== null) {
            $tagsAsArray = preg_split("/,/", $newsItem->tags);
            if ($tagsAsArray !== false) {
                $newsItem->tags = $tagsAsArray;
            } else {
                $newsItem->tags = [$newsItem->tags];
            }
        }

        if ($newsItem->country !== null) {
            $countriesAsArray = preg_split("/,/", $newsItem->country);
            if ($countriesAsArray !== false) {
                $newsItem->country = $countriesAsArray;
            } else {
                $newsItem->country = [$newsItem->country];
            }
        } else {
            $newsItem->country = [env('DEFAULT_COUNTRY')];
        }

        return $newsItem;
    }

    public static function replaceText(News $newsItem): News
    {
        $textWithTags = (new NewsTextConverter())
            ->setText($newsItem->text)
            ->cutGalleryTags()
            ->changeTextLinks()
            ->getText();
        unset($newsItem->text);

        $newsItem->newsText = [
            "textWithTags" => $textWithTags,
            "textSource" => $newsItem->textSrc,
            "link" => sprintf(
                "http://mir24.tv/news/%d/%s",
                $newsItem->id,
                (new Transliterator())->toUrl($newsItem->title)
            )
        ];
        unset($newsItem->textSrc);

        return $newsItem;
    }

    public function scopeGetIdsWithCountryAndCategery(Builder $query): Builder
    {
        return $query->select(["id", "country_id as countryId", "categoryID as categoryId"])
            ->leftJoin("news_country", "news_country.news_id", '=', "news.id")
            ->where("news.published", true)
            ->orderBy("news.id", "DESC")
            ->limit(100000);
    }
}
