<?php

namespace App\Library\Services\Import;

use App\ActualNews;
use DiDom\Document;
use DiDom\Query;
use Illuminate\Support\Facades\DB;

class Mir24Importer
{
    private const DEFAULT_UPDATE_PERIOD_IN_MINUTES = 60; // период обновления новостей в минутах

    public function getUpdatePeriod(?int $default): int
    {
        return $default ? $default : $this::DEFAULT_UPDATE_PERIOD_IN_MINUTES;
    }

    public function setUpdateComplete(bool $status)
    {
        $query = "UPDATE status SET int_value=? WHERE variable_name = 'UPDATE_COMPLETE'";
        DB::update($query, [$status]);
    }

    public function getLastNews(?int $period): array
    {
        $query = "SELECT    n.id, n.created_at as date, n.published_at, n.advert as shortText, n.text, "
            . "          n.title, imn.image_id as imageId, t.id AS rubric_id, UPPER(t.title) AS categoryName, "
            . "          nv.video_id as videoId, v.url AS videoUrl, v.duration AS videoDuration, a.name AS author, "
            . "          c.origin, c.link, n.main_top as topListNews, "
            . "          (n.status = 'active') AS published, "
            . "          (nt1.tag_id IS NOT NULL) AS hasGallery "
            . "FROM      news n "
            . "LEFT JOIN news_tag nt ON nt.news_id = n.id "
            . "LEFT JOIN tags t ON t.id = nt.tag_id AND t.type = 3 "
            . "LEFT JOIN news_tag nt1 ON nt1.news_id = n.id AND nt1.tag_id = '4459785' "
            . "LEFT JOIN news_video nv ON nv.news_id = n.id "
            . "LEFT JOIN image_news imn ON imn.news_id = n.id AND imn.image_id = "
            . "          (SELECT image_id FROM image_news WHERE news_id = n.id LIMIT 1) "
            . "LEFT JOIN copyright_news cn ON cn.news_id = n.id "
            . "LEFT JOIN images i ON i.id = imn.image_id "
            . "LEFT JOIN copyrights c ON c.id = i.copyright_id "
            . "LEFT JOIN authors a ON a.id = i.author_id "
            . "LEFT JOIN videos v ON v.id = nv.video_id "
            . "WHERE     n.title IS NOT NULL "
            . "AND       n.text  IS NOT NULL "
            . "AND       t.type = 3 "
            . "AND       ((n.created_at > (NOW() - INTERVAL " . ($this->getUpdatePeriod($period) + 1) . " MINUTE) "
            . "   OR       n.updated_at > (NOW() - INTERVAL " . ($this->getUpdatePeriod($period) + 1) . " MINUTE))"
            . "     OR     n.id > ?)";

        $lastNewsId = $this->getLastNewsId();

        $resultSet = DB::connection('mir24')->select($query, [$lastNewsId]);

        $news = $this->parseItemsFromResultSet($resultSet);
        $news = $this->filterNewsForAvailableCategories($news, $this->getAvailableCategories());

        return $news;
    }

    private function textForMobile($text)
    {
        if (!$text || $text == "") {
            return $text;
        }

        $text = htmlspecialchars_decode($text);

        $pattern = '/{(.*?)}/i';
        $text = preg_replace($pattern, "", $text);

        $dom = new Document();
        $dom->loadHtml($text);

        $images = $dom->find('img', Query::TYPE_CSS);

        $iframs = $dom->find('iframe', Query::TYPE_CSS);

        foreach ($images as $image) {
            $image->attr("width", "90%")
                ->attr("style", "padding:5px;")
                ->removeAttribute("height");
        }

        foreach ($iframs as $ifram) {
            $ifram->remove();
        }

        // чистит текст оставляя только "базовые теги" + для img разрешён атрибут style
        // String safe = Jsoup.clean(doc.toString(), "https://mir24.tv/", Whitelist.basicWithImages().addAttributes("img", "style"));

        return $dom->first('body')->innerHtml();
    }

    private function parseItemsFromResultSet($news)
    {

        foreach ($news as $item) {

            $item->text = $this->textForMobile($item->text);

            if ($item->origin == null) {
                $item->origin = "";
            }
            if ($item->link == null) {
                $item->link = "";
            }
            if ($item->author == null || $item->author == "Автор не указан" || $item->author == "не указан") {
                $item->author = "";
            } else {
                $item->author = "Фото: " . $item->author . " ";
            }
            $item->copyright = $item->author . "<a href='" . $item->link . "'>" . $item->origin . "</a>";
            $item->copyrightSrc = $item->author . $item->origin;
        }

        return $news;
    }

    private function filterNewsForAvailableCategories($news, $availableCategories)
    {
        $filtered = [];

        foreach ($news as $item) {
            switch ($item->categoryName) {
                //change last english C on full russian name
                case "ШОУ-БИЗНЕC":
                    $item->categoryName = "ШОУ-БИЗНЕС";
                    break;
                case "НАУКА И ТЕХНОЛОГИИ":
                    $item->categoryName = $item->id % 2 == 0 ? "НАУКА" : "HI-TECH";
                    break;
            }

            # TODO categoryName может не оказаться в $availableCategories из-за замены в switch/case
            if (array_key_exists($item->categoryName, $availableCategories)) {
                $categoryId = $availableCategories[$item->categoryName];

                if ($categoryId != null) {
                    $item->categoryId = $categoryId;
                    $filtered[] = $item;
                }
            }
        }

        return $filtered;
    }

    private function getLastNewsId(): int
    {
        $query = "SELECT MAX(id) AS lastId FROM news";

        $result = DB::connection('mir24')->select($query);

        if (count($result)) {
            return $result[0]->lastId;
        } else {
            return 0;
        }
    }

    public function saveLastNews($news): void
    {
        $query = "INSERT INTO news (id, date, title, shortText, shortTextSrc, text, textSrc, "
            . "                  imageID, categoryID, videoID, "
            . "                  copyright, copyrightSrc, topListNews, "
            . "                  hasGallery, published, videoDuration) "
            . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "
            . "ON DUPLICATE KEY UPDATE "
            . "       id = VALUES(id), date = VALUES(date), title = VALUES(title), "
            . "       shortText = VALUES(shortText), shortTextSrc = VALUES(shortTextSrc), "
            . "       text = VALUES(text), textSrc = VALUES(textSrc), imageID = "
            . "       VALUES(imageID), categoryID = VALUES(categoryID), videoID = VALUES(videoID), "
            . "       copyright = VALUES(copyright), copyrightSrc = VALUES(copyrightSrc), "
            . "       topListNews = VALUES(topListNews), "
            . "       hasGallery = VALUES(hasGallery), published = VALUES(published), "
            . "       videoDuration = VALUES(videoDuration)";

        foreach ($news as $newsItem) {
            DB::insert($query, [
                $newsItem->id,
                $newsItem->date,
                $newsItem->title,
                $newsItem->shortText,
                $newsItem->shortText,
                $newsItem->text,
                $newsItem->text,
                $newsItem->imageId,
                $newsItem->categoryId,
                $newsItem->videoId,
                $newsItem->copyright,
                $newsItem->copyrightSrc,
                $newsItem->topListNews ?? 0,
                $newsItem->hasGallery,
                $newsItem->published,
                gmdate("H:i:s", $newsItem->videoDuration ?? 0)
            ]);
        }
    }

    /**
     * This method must be reworked to get values from native mir24 api because
     * it's have hardcoded values of static tags in it
     *
     * @return array of actual news id
     */
    public function getActualNewsId(): array
    {
        $news = [];

        $queryPromo = "SELECT entity_id FROM promo_cells LIMIT " . ActualNews::PROMO_NEWS_COUNT;
        $rsPromo = DB::connection('mir24')->select($queryPromo);

        foreach ($rsPromo as $row) {
            $news[] = $row->entity_id;
        }

        if (count($news) < ActualNews::PROMO_NEWS_COUNT) {
            $limitAdditional = ActualNews::PROMO_NEWS_COUNT - count($news);

            $queryAdditional = "SELECT news.id FROM news "
                . "WHERE EXISTS (SELECT * FROM images INNER JOIN image_news"
                . "              ON images.id = image_news.image_id "
                . "              WHERE image_news.news_id = news.id "
                . "              AND images.deleted_at IS NULL) "
                . "AND NOT EXISTS (SELECT * FROM tags INNER JOIN news_tag "
                . "                ON tags.id = news_tag.tag_id "
                . "                WHERE news_tag.news_id = news.id "
                . "                AND id IN (15348025, 15348024, 15348023, 15348067, 15348062) "
                . "                AND tags.deleted_at IS NULL) "
                . "AND main_top = 1 and news.deleted_at IS NULL "
                . "AND news.status = ? ORDER BY published_at DESC LIMIT $limitAdditional OFFSET 0";

            $rsAdditional = DB::connection('mir24')->select($queryAdditional, ["active"]);

            foreach ($rsAdditional as $row) {
                $news[] = $row->id;
            }
        }

        return $news;
    }

    public function updateActualNews($actualNews): void
    {
        $queryDelete = "DELETE FROM actual_news";
        $queryInsert = "INSERT IGNORE INTO actual_news(id, news_id) VALUES (?,?) "
            . "ON DUPLICATE KEY "
            . "UPDATE id = VALUES(id), news_id = VALUES(news_id)";

        DB::delete($queryDelete);
        foreach ($actualNews as $i => $newsId) {
            DB::insert($queryInsert, [$i, $newsId]);
        }
    }

    public function getTags(?int $period): array
    {
        $query = "SELECT id, title as name "
            . "FROM   tags "
            . "WHERE  type = 1 "
            . "AND    (created_at > (NOW() - INTERVAL " . ($this->getUpdatePeriod($period) + 1) . " MINUTE) "
            . "OR      updated_at > (NOW() - INTERVAL " . ($this->getUpdatePeriod($period) + 1) . " MINUTE)) "
            . "OR     id > ?";

        $lastTagId = $this->getLastTagId();

        return DB::connection('mir24')->select($query, [$lastTagId]);
    }

    private function getLastTagId()
    {
        $query = "SELECT MAX(id) AS lastId FROM tags";

        $result = DB::connection('mir24')->select($query);

        if (count($result)) {
            return $result[0]->lastId;
        } else {
            return 0;
        }

    }

    public function saveTags($tags): void
    {
        $query = "INSERT INTO tags (id, name) "
            . "VALUES (?,?) "
            . "ON DUPLICATE KEY "
            . "UPDATE name = VALUES(name)";

        foreach ($tags as $tag) {
            DB::insert($query, [$tag->id, $tag->name]);
        }
    }

    public function getNewsTags($news): array
    {
        if ($news == null || count($news) == 0) {
            return [];
        }

        $whereIn = implode(',', array_fill(0, count($news), '?'));
        $query = "SELECT nt.news_id as newsId, nt.tag_id as tagId, (nt.status = 'active') AS published "
            . "FROM      news_tag nt "
            . "LEFT JOIN tags t ON t.id = nt.tag_id "
            . "WHERE     t.type = 1 AND nt.news_id in ($whereIn)";

        $ids = array_map(function ($row) {
            return $row->id;
        }, $news);

        $newsTags = [];

        $rs = DB::connection('mir24')->select($query, $ids);
        foreach ($rs as $row) {
            $newsTags[$row->newsId][] = $row;
        }

        return $newsTags;
    }

    public function saveNewsTags($newsTags): void
    {
        $queryDelete = "DELETE FROM news_tags WHERE news_id = ?";
        $queryInsert = "INSERT IGNORE INTO news_tags(news_id, tag_id) VALUES (?, ?)";

        foreach ($newsTags as $newsId => $tags) {
            DB::delete($queryDelete, [$newsId]);
            foreach ($tags as $tag) {
                DB::insert($queryInsert, [$tag->newsId, $tag->tagId]);
            }
        }
    }

    public function getGalleries(?array $news): array
    {
        if ($news == null || count($news) == 0) {
            return [];
        }

        $ids = [];
        foreach ($news as $newsItem) {
            if ($newsItem->hasGallery) {
                $ids[] = $newsItem->id;
            }
        }

        if (count($ids) === 0) {
            return [];
        }

        $whereIn = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT imn.news_id AS newsId, imn.image_id AS imageId, i.src AS link, a.name AS author "
            . "FROM      image_news imn "
            . "LEFT JOIN images i ON i.id = imn.image_id "
            . "LEFT JOIN authors a ON a.id = i.author_id "
            . "WHERE     imn.news_id in ($whereIn)";

        $rs = DB::connection('mir24')->select($query, $ids);

        $galleries = [];
        foreach ($rs as $row) {
            $galleries[$row->newsId][] = $row;
        }

        return $galleries;
    }

    public function saveGalleries($galleries): void
    {
        $queryDelete = "DELETE FROM photos WHERE news_id = ?";

        $queryInsert = "INSERT INTO photos(author, link, news_id, image_id) "
            . "VALUES (?, ?, ?, ?) "
            . "ON DUPLICATE KEY UPDATE "
            . "   author = VALUES(author), link = VALUES(link), "
            . "   news_id = VALUES(news_id), image_id = VALUES(image_id)";

        foreach ($galleries as $newsId => $photos) {
            DB::delete($queryDelete, [$newsId]);
            foreach ($photos as $photo) {
                DB::insert($queryInsert, [$photo->author, $photo->link, $newsId, $photo->imageId]);
            }
        }
    }

    private function getAvailableCategories(): array
    {
        $query = "SELECT UPPER(name) AS name, id FROM categories WHERE `show` = 'true'";

        $availableCategories = [];
        $rs = DB::select($query);
        foreach ($rs as $row) {
            $availableCategories[$row->name] = $row->id;
        }

        return $availableCategories;
    }

    public function getNewsCountryLinks($news): array
    {
        if ($news == null || count($news) == 0) {
            return [];
        }

        $whereIn = implode(',', array_fill(0, count($news), '?'));
        $query = "SELECT nt.news_id news_id, UPPER(t.title) AS country "
            . "FROM   news_tag nt "
            . "LEFT JOIN tags t "
            . "ON t.id = nt.tag_id "
            . "WHERE  nt.news_id in ($whereIn) "
            . "AND    t.type = 2 "
            . "AND    status = 'active'";

        $countries = $this->getAvailableCountries();

        $ids = array_map(function ($i) {
            return $i->id;
        }, $news);

        $rs = DB::connection('mir24')->select($query, $ids);

        foreach ($rs as $row) {
            $row->country = key_exists($row->country, $countries)
                ? $countries[$row->country]
                : $countries["РОССИЯ"];
        }

        return $rs;
    }

    public function saveNewsCountryLinks($links): void
    {
        $query = "INSERT IGNORE INTO news_country (`news_id`, `country_id`) VALUES (?, ?)";

        foreach ($links as $link) {
            DB::insert($query, [$link->news_id, $link->country]);
        }
    }

    private function getAvailableCountries(): array
    {
        $query = "SELECT UPPER(name) AS name, id FROM country WHERE published = 'true'";

        $rs = DB::select($query);

        $countries = [];
        foreach ($rs as $row) {
            $countries[$row->name] = $row->id;
        }

        return $countries;
    }
}
