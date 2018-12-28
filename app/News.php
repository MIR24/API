<?php

namespace App;


use App\Library\Components\EloquentOptions\NewsOption;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class News extends Model
{
    protected $table = 'news';

    private const DEFAULT_COUNTRY = 4453; # TODO

    public function scopeGetText(Builder $query, $newsId): Builder
    {
        return $query->where('id', $newsId); # TODO
    }

    public function scopeGetList(Builder $query, NewsOption $options): Builder
    {
        $fieldsForSelect = "news.id, date, shortText, shortTextSrc, textSrc, title, imageID, "
            . "       categoryID, serieID, videoID, episodeID, copyright, copyrightSrc, "
            . "       rushHourNews, topListNews, hasGallery, videoDuration, (SELECT GROUP_CONCAT("
            . "       tag_id SEPARATOR ',') FROM news_tags WHERE news_id = news.id) AS tags, "
            . "       (SELECT GROUP_CONCAT(country_id SEPARATOR ',') FROM news_country "
            . "       WHERE news_id = news.id) AS country ";

        $query->select(DB::raw($fieldsForSelect));

//        if (options.getPreSearch() != null) {
//            ArrayList<Integer> preSearch = options.getPreSearch();
//            if (options.getPreSearch().isEmpty()) {
//                return news;
//            } else {
//                query = query.concat("WHERE n.id IN (");
//
//                for (Integer id : preSearch) {
//                    query = query.concat(id + ",");
//                }
//                query = query.substring(0, query.length() - 1).concat(") ORDER BY categoryID, id DESC");
//            }
//        } else {
        $this->optionToWhere($query, $options);
//        }

//        $news = $this->postQueryList($news);
////        if ($options.getActual()
////            && options.getPage() == 1
////            && news.size() < SiteConfig.PROMO_NEWS_COUNT
////           ) {
//            $this->updateActual();
////      }

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
                ->offset($options->getLimit() * ($options->getPage() - 1));
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

        if ($options->getTags() !== null && count($options->getTags()) && !$options->isLastNews()) {
            $tagsAsArray = preg_split("/,/", $options->getTags());
            $query->rightJoinWhere("news_tags as nt", "news.id", "=", "nt.news_id")
                ->where("nt.tag_id", "IN", $tagsAsArray);
        }

//            if (options.getCountryID() != null) {
//                String index = "country_id";
//                if (options.getCountryID().equals(4453)) {
//                    index = "news_country";
//                }
//                query = query.concat("RIGHT JOIN news_country nc "
//                        + "USE INDEX (" + index + ") "
//                        + "ON n.id = nc.news_id "
//                        + "AND nc.country_id = " + options.getCountryID() + " ");
//            }
//            if (options.getIgnoreId() != null && options.getIgnoreId().length > 0) {
//                query = query.concat("n.id NOT IN ("
//                        + ArrayUtils.Join(options.getIgnoreId(), ",")) + ") AND ";
//            }
    }

    public static function postprocessingOfGetList($newsItem, NewsOption $options)
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
            $newsItem->country = [self::DEFAULT_COUNTRY];
        }

        return $newsItem;
    }

    private function updateActual()
    {
//            options.setActual(Boolean.FALSE);
//            int[] ignoreId = new int[news.size()];
//            for (int i = 0; i < ignoreId.length; i++) {
//                ignoreId[i] = news.get(i).getId();
//            }
//            options.setIgnoreId(ignoreId);
//            options.setLimit(SiteConfig.PROMO_NEWS_COUNT - news.size());
//            news.addAll(getNewsList(options));
    }
}
///**
// * Get text of news with specified id.
// *
// * @param newsID
// * @return NewsText object which contains text with and without html tags
// */
//public NewsText getNewsText(int newsID) {
//    query = "SELECT title, text, textsrc, hasGallery, url "
//        + "FROM   news n "
//        + "LEFT JOIN categories c ON c.id = n.categoryID "
//        + "WHERE n.id = '" + newsID + "'";
//    DBMessanger messanger = new DBMessanger("m24api");
//        ResultSet set = messanger.doQuery(query);
//        NewsText newsText = new NewsText();
//        try {
//            if (set.next()) {
//                String text = set.getString("text");
//                String title = set.getString("title");
//                text = cutGalleryTags(text);
//                text = changeTextLinks(text);
//                newsText.setTextWithTags(text);
//                newsText.setTextSource(set.getString("textsrc"));
//                newsText.setLink("http://mir24.tv/news/" + newsID + "/" + Transliterator.toTranslit(title).toLowerCase());
//            }
//        } catch (SQLException ex) {
//        logger.error("Can't get newstext: " + ex.toString());
//    } finally {
//        messanger.closeConnection();
//    }
//        return newsText;
//    }
