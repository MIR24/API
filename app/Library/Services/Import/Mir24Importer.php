<?php

namespace App\Library\Services\Import;

use Illuminate\Support\Facades\DB;

class Mir24Importer
{
    private const PROMO_NEWS_COUNT = 5;
    private const UPDATE_PERIOD_IN_MINUTES = 60; // период обновления новостей в минутах

    public function getLastNews(): array
    {
        $query = "SELECT    n.id, n.created_at, n.published_at, n.advert, n.text, "
            . "          n.title, imn.image_id, t.id AS rubric_id, UPPER(t.title) AS category_name, "
            . "          nv.video_id, v.url AS video_url, a.name AS author, "
            . "          c.origin, c.link, n.lightning, n.main_top, "
            . "          (n.status = 'active') AS published, "
            . "          n.main_center, (nt1.tag_id IS NOT NULL) AS with_gallery "
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
            . "AND       ((n.created_at > (NOW() - INTERVAL " . ($this::UPDATE_PERIOD_IN_MINUTES + 1) . " MINUTE) "
            . "   OR       n.updated_at > (NOW() - INTERVAL " . ($this::UPDATE_PERIOD_IN_MINUTES + 1) . " MINUTE))"
            . "     OR     n.id > ?)";
//        query = "SELECT    n.id, n.created_at, n.published_at, n.advert, n.text, " +
//            "          n.title, im.image_id, t.id AS rubric_id, nv.video_id, " +
//            "          c.origin, c.link, n.lightning, n.main_top, (n.status = 'active') AS published, " +
//            "          n.main_center, (t1.id IS NULL) AS with_gallery " +
//            "FROM      news n " +
//            "LEFT JOIN image_news im ON im.news_id = n.id " +
//            "LEFT JOIN news_tag nt ON nt.news_id = n.id " +
//            "LEFT JOIN tags t ON t.id = nt.tag_id AND t.type = 3 " +
//            "LEFT JOIN tags t1 ON t1.id = nt.tag_id AND t1.title = 'фото' " +
//            "LEFT JOIN news_video nv ON nv.news_id = n.id " +
//            "LEFT JOIN copyright_news cn ON cn.news_id = n.id " +
//            "LEFT JOIN copyrights c ON cn.copyright_id = c.id " +
//            "WHERE     n.title IS NOT NULL " +
//            "AND       n.text  IS NOT NULL " +
//            "AND       t.type = 3 " +
//            "AND       ((n.created_at > (NOW() - INTERVAL " + (UPDATE_PERIOD + 1) + " MINUTE) " +
//            "   OR       n.updated_at > (NOW() - INTERVAL " + (UPDATE_PERIOD + 1) + " MINUTE))" +
//            "     OR     n.id > " + lastNewsId + ")";

        $lastNewsId = $this->getLastNewsId();

        $resultSet = DB::connection('mir24')->select($query, [$lastNewsId]);

        $news = $this->parseItemsFromResultSet($resultSet);
// TODO       news = filterNewsForAvailableCategories(news, getAvailableCategories());

        return $news;
    }

    private function parseItemsFromResultSet($rs) {
        return $rs;
//                item.setDate(resultSet.getTimestamp("created_at"));
//                item.setShortText(resultSet.getString("advert"));
//                item.setShortTextSrc(resultSet.getString("advert"));

//                $text = $rs.getString("text");
//                text = text.replaceAll("\\{(.*)\\}", "");
//                Document doc = Jsoup.parse(StringEscapeUtils.unescapeHtml4(text));
//                for (Element el : doc.getAllElements()) {
//                    if (el.tagName().equals("img")) {
//                        el.attr("width", "90%");
//                        el.attr("style", "padding:5px;");
//                        el.removeAttr("height");
//                    } else if (el.tagName().equals("iframe")) {
//                        el.remove();
//                    }
//                }
//                String safe = Jsoup.clean(doc.toString(), "https://mir24.tv/",
//                            Whitelist.basicWithImages().addAttributes("img", "style"));
//                item.setText(safe);
//                item.setTextSrc(safe);
//
//                item.setSerieID(null);
//                item.setEpisodeID(null);
//
//                String origin = resultSet.getString("origin");
//                String link = resultSet.getString("link");
//                String author = resultSet.getString("author");
//                if (origin == null) {
//                    origin = "";
//                }
//                if (link == null) {
//                    link = "";
//                }
//                if (author == null || author.equals("Автор не указан") || author.equals("не указан")) {
//                    author = "";
//                } else {
//                    author = "Фото: " + author + " ";
//                }
//                item.setCopyright(author + "<a href='" + link + "'>" + origin + "</a>");
//                item.setCopyrightSrc(author + origin);
//
//                item.setRushHourNews(resultSet.getBoolean("lightning"));
//                item.setTopListNews(resultSet.getBoolean("main_top"));
//                item.setHasGallery(resultSet.getBoolean("with_gallery"));
//                item.setPublished(resultSet.getBoolean("published"));
//                item.setOnMainPagePosition(resultSet.getInt("main_center"));
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
        # TODO
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

        $queryPromo = "SELECT entity_id FROM promo_cells LIMIT " . $this::PROMO_NEWS_COUNT;
        $rsPromo = DB::connection('mir24')->select($queryPromo);

        foreach ($rsPromo as $row) {
            $news[] = $row->entity_id;
        }

        if (count($news) < $this::PROMO_NEWS_COUNT) {
            $limitAdditional = $this::PROMO_NEWS_COUNT - count($news);

            # TODO первый подселект был с ошибками. Запрос никогда не вызывался?
            # TODO ??? AND id IN (15348025, 15348024, 15348023, 15348067, 15348062) "
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
//public HashMap<Integer, Integer> getActualNews(){
//
//HashMap<Integer,Integer> news        = new HashMap<Integer, Integer>();
//DBMessanger              pgMessanger = new DBMessanger("mir24");
//
//query = "SELECT    id " +
//"FROM      news " +
//"WHERE     lightning = '1' AND status = 'active' " +
//"ORDER BY  id DESC " +
//"LIMIT     200";
//
//ResultSet resultSet = pgMessanger.doQuery(query);
//
//try {
//int i = 0;
//while(resultSet.next()){
//news.put(i++, resultSet.getInt("id"));
//}
//}
//catch(SQLException sqlex){
//    logger.error("Can't get actual news from db: " + sqlex);
//}
//    finally {
//    pgMessanger.closeConnection();
//}
//
//    return news;
//  }
//
//  private void dropActualNews(){
//query = "DELETE FROM actual_news";
//    DBMessanger messanger = new DBMessanger("m24api");
//    messanger.doUpdate(query);
//    messanger.closeConnection();
//  }

    public function updateActualNews($actualNews): void
    {
        # TODO
    }

    public function getTags(): array
    {
        $query = "SELECT id, title as name "
            . "FROM   tags "
            . "WHERE  type = 1 "
            . "AND    (created_at > (NOW() - INTERVAL " . ($this::UPDATE_PERIOD_IN_MINUTES + 1) . " MINUTE) "
            . "OR      updated_at > (NOW() - INTERVAL " . ($this::UPDATE_PERIOD_IN_MINUTES + 1) . " MINUTE)) "
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
        $query = "SELECT nt.tag_id, (nt.status = 'active') AS published "
            . "FROM      news_tag nt "
            . "LEFT JOIN tags t ON t.id = nt.tag_id "
            . "WHERE     t.type = 1 AND nt.news_id in ($whereIn)";

        $ids = array_map(function ($row) {
            return $row->id;
        }, $news);

        return DB::connection('mir24')->select($query, $ids);
    }

    public function saveNewsTags($newsTags): void
    {
        # TODO
    }

    public function getGalleries(?array $news): array
    {
        if ($news == null || count($news) == 0) {
            return [];
        }

        $whereIn = implode(',', array_fill(0, count($news), '?'));
        $query = "SELECT imn.news_id AS newsId, imn.image_id AS imageId, i.src AS link, a.name AS author "
            . "FROM      image_news imn "
            . "LEFT JOIN images i ON i.id = imn.image_id "
            . "LEFT JOIN authors a ON a.id = i.author_id "
            . "WHERE     imn.news_id in ($whereIn)";

        $ids = array_map(function ($i) {
            return $i->id;
        }, $news);

        return DB::connection('mir24')->select($query, $ids);
    }

    public function saveGalleries($galleries): void
    {
        # TODO
    }

    public function getCategories(): array
    {
        $query = "SELECT id, title as name, translateTitle AS url, deleted_at, priority "
            . "FROM   tags "
            . "WHERE  title IS NOT NULL "
            . "AND    type = 3 "
            . "AND    (deleted_at IS NULL OR deleted_at >= DATE_SUB(CURDATE(), INTERVAL 12 HOUR))";

        return DB::connection('mir24')->select($query);
    }

    public function updateCategories(array $categories): void
    {
        $insertCategories = [];

        foreach ($categories as $category) {
            if ($category->deleted_at != null) {
                $this->removeCategory($category->id);
            } else {
                $insertCategories[] = $category;
            }
        }

        $this->saveCategories($insertCategories);
    }

    private function saveCategories($categories)
    {
        $query = "INSERT INTO categories (`id`, `name`, `url`, `order`) "
            . "VALUES (?, ?, ?, ?)"
            . "ON DUPLICATE KEY UPDATE "
            . "   id = VALUES(id), name = VALUES(name), url = VALUES(url), "
            . "   `order` = VALUES(`order`)";

        foreach ($categories as $category) {
            DB::insert($query, [$category->id, $category->name, $category->url, $category->priority ?? 0]);
        }
    }

    private function removeCategory($id)
    {
        $query = "DELETE FROM categories WHERE id = ?";
        DB::delete($query, [$id]);
    }

    public function getCountries(): array
    {
        $query = "SELECT  id, (deleted_at IS NULL) AS published, UPPER(title) as name "
            . "FROM    tags "
            . "WHERE   type = 2 ";

        return DB::connection('mir24')->select($query);
    }

    public function saveCountries(array $countries): void
    {
        $query = "INSERT INTO country (`id`,`name`,`published`) "
            . "VALUES (?, ?, ?) "
            . "ON DUPLICATE KEY UPDATE name=?, published=?";

        foreach ($countries as $country) {
            DB::insert(
                $query,
                [$country->id, $country->name, $country->published, $country->name, $country->published]
            );
        }
    }

    public function getNewsCountryLinks($news): array
    {
        if ($news == null || count($news) == 0) {
            return [];
        }

        //        int lastNewsId = getLastCountryLinkId();

        $whereIn = implode(',', array_fill(0, count($news), '?'));
        $query = "SELECT nt.news_id news_id, UPPER(t.title) AS country "
            . "FROM   news_tag nt "
            . "LEFT JOIN tags t "
            . "ON t.id = nt.tag_id "
            . "WHERE  nt.news_id in ($whereIn) "
            . "AND    t.type = 2 "
            . "AND    status = 'active'";
//        query = "SELECT nt.news_id news_id, nt.tag_id tag_id " +
//            "FROM   news_tag nt " +
//            "LEFT JOIN tags t " +
//            "ON t.id = nt.tag_id " +
//            "WHERE  nt.news_id > " + (lastNewsId - 100) + " " +
//            "AND    t.type = 2 " +
//            "AND    status = 'active'";

        $countries = $this->getAvailableCountries(); # TODO

        $ids = array_map(function ($i) {
            return $i->id;
        }, $news);

        $rs = DB::connection('mir24')->select($query, $ids);

//        foreach ($rs as $row) { TODO need $countries
//            $row->country = $countries[$row->country];
//            if ($row->country == null) {
//                $row->country = $countries["РОССИЯ"]; # TODO
//            }
//        }

        return $rs;
    }

//private HashMap<Integer, Integer> getNewsCountryLinks(){
//
//HashMap<Integer, Integer> links = new HashMap<>();
//
//int lastNewsId = getLastCountryLinkId();
//
//DBMessanger messanger = new DBMessanger("mir24");
//
//query = "SELECT nt.news_id news_id, nt.tag_id tag_id " +
//"FROM   news_tag nt " +
//"LEFT JOIN tags t " +
//"ON t.id = nt.tag_id " +
//"WHERE  nt.news_id > " + (lastNewsId - 100) + " " +
//"AND    t.type = 2 " +
//"AND    status = 'active'";
//
//ResultSet resultSet = messanger.doQuery(query);
//
//try {
//while(resultSet.next()){
//links.put(resultSet.getInt("news_id"), resultSet.getInt("tag_id"));
//}
//}
//catch(SQLException sqlex){
//    logger.error("Can't get country links: " + sqlex);
//}
//    finally {
//    messanger.closeConnection();
//}
//
//    return links;
//  }

    private function getAvailableCountries(): array
    {
        return []; # TODO Table 'mir24_7.country' doesn't exist

        $query = "SELECT UPPER(name) AS name, id FROM country WHERE published = 'true'";

        return DB::connection('mir24')->select($query);
    }

    public function saveNewsCountryLinks($links): void
    {
        # TODO
    }
}

///*  Класс содержащий методы для парсинга информации из базы данных bd-mir основного
// *  сайта и сохранения их в локальную базу на bd-proj. Используется для снижения нагрузки
// *  на основную базу и снижения количества запросов к ней.
// */
///**
// * Class used to parse news from main site database and save them to local api
// * database.
// *
// * @author babikov_pv
// */
//public class NewsParser extends Thread {
//
//private final String DEFAULT_VIDEO_URL;
//
//    private void saveGalleries(HashMap<Integer, Set<Gallery>> galleries) throws SQLException {
//
//    query = "INSERT INTO photos(author, link, news_id, image_id) "
//        + "VALUES (?, ?, ?, ?) "
//        + "ON DUPLICATE KEY UPDATE "
//        + "   author = VALUES(author), link = VALUES(link), "
//        + "   news_id = VALUES(news_id), image_id = VALUES(image_id)";
//
//    DBMessanger messanger = new DBMessanger("m24api");
//        Connection connection = messanger.getConnection();
//
//        PreparedStatement addStatement = null;
//        Statement dropStatement = null;
//
//        Set<Integer> news = galleries.keySet();
//        Iterator<Integer> it = news.iterator();
//
//        connection.setAutoCommit(Boolean.FALSE);
//
//        try {
//            addStatement = connection.prepareStatement(query);
//            dropStatement = connection.createStatement();
//
//            while (it.hasNext()) {
//                Integer newsId = it.next();
//                dropStatement.execute("DELETE FROM photos WHERE news_id = " + newsId);
//                Set<Gallery> photos = galleries.get(newsId);
//                for (Gallery photo : photos) {
//                    addStatement.setString(1, photo.getAuthor());
//                    addStatement.setString(2, photo.getLink());
//                    addStatement.setInt(3, photo.getNewsID());
//                    addStatement.setInt(4, photo.getImageID());
//                    addStatement.execute();
//                }
//            }
//
//            connection.commit();
//        } catch (SQLException sqlex) {
//        connection.rollback();
//        logger.error("Can't save galleries. Rolled back. Error: " + sqlex);
//    } finally {
//        if (addStatement != null) {
//            addStatement.close();
//        }
//        if (dropStatement != null) {
//            dropStatement.close();
//        }
//        connection.setAutoCommit(Boolean.TRUE);
//        connection.close();
//    }
//    }
//
//    private void saveNewsCountryLinks(HashMap<Integer, Integer> links) {
//
//DBMessanger messanger = new DBMessanger("m24api");
//        Set<Integer> keySet = links.keySet();
//        for (Integer newsId : keySet) {
//            Integer countryId = links.get(newsId);
//
//            query = "INSERT IGNORE INTO news_country (`news_id`, `country_id`) "
//                + "VALUES ('" + newsId + "', '" + countryId + "')";
//
//            messanger.doUpdate(query);
//        }
//
//        messanger.closeConnection();
//
//    }
//
//    private HashMap<String, Integer> getAvailableCategories() {
//
//            DBMessanger messanger = new DBMessanger("m24api");
//        HashMap<String, Integer> categories = new HashMap<>();
//
//        query = "SELECT UPPER(name) AS name, id FROM categories WHERE `show` = 'true'";
//
//        ResultSet rs = messanger.doQuery(query);
//        try {
//            while (rs.next()) {
//                categories.put(rs.getString("name"), rs.getInt("id"));
//            }
//        } catch (SQLException sqlex) {
//                logger.error("Can't get available categories for api database: " + sqlex);
//            } finally {
//                messanger.closeConnection();
//            }
//
//        return categories;
//    }
//
//    private ArrayList<NewsItem> filterNewsForAvailableCategories(
//                ArrayList<NewsItem> news, HashMap<String, Integer> availableCategories) {
//                ArrayList<NewsItem> filtered = new ArrayList<>();
//        for (NewsItem item : news) {
//
//            //change last english C on full russian name
//            switch (item.getCategoryName()) {
//                case "ШОУ-БИЗНЕC":
//                    item.setCategoryName("ШОУ-БИЗНЕС");
//                    break;
//                case "НАУКА И ТЕХНОЛОГИИ":
//                    item.setCategoryName(item.getId() % 2 == 0 ? "НАУКА" : "HI-TECH");
//                    break;
//            }
//
//            Integer categoryId = availableCategories.get(item.getCategoryName());
//            if (categoryId != null) {
//                item.setCategoryID(categoryId);
//                filtered.add(item);
//            }
//        }
//        return filtered;
//    }
//
//
//    private ArrayList<NewsItem> parseItemsFromResultSet(ResultSet resultSet) {
//
//                ArrayList<NewsItem> news = new ArrayList<>();
//
//        try {
//            while (resultSet.next()) {
//                NewsItem item = new NewsItem();
//                item.setId(resultSet.getInt("id"));
//                item.setDate(resultSet.getTimestamp("created_at"));
//                item.setTitle(resultSet.getString("title"));
//                item.setShortText(resultSet.getString("advert"));
//                item.setShortTextSrc(resultSet.getString("advert"));
//                String text = resultSet.getString("text");
//                text = text.replaceAll("\\{(.*)\\}", "");
//                Document doc = Jsoup.parse(StringEscapeUtils.unescapeHtml4(text));
//                for (Element el : doc.getAllElements()) {
//                    if (el.tagName().equals("img")) {
//                        el.attr("width", "90%");
//                        el.attr("style", "padding:5px;");
//                        el.removeAttr("height");
//                    } else if (el.tagName().equals("iframe")) {
//                        el.remove();
//                    }
//                }
//                String safe = Jsoup.clean(doc.toString(), "https://mir24.tv/",
//                            Whitelist.basicWithImages().addAttributes("img", "style"));
//                item.setText(safe);
//                item.setTextSrc(safe);
//                item.setImageID(resultSet.getInt("image_id"));
//                item.setCategoryID(resultSet.getInt("rubric_id"));
//                item.setCategoryName(resultSet.getString("category_name"));
//                item.setSerieID(null);
//                item.setVideoID(resultSet.getInt("video_id"));
//                item.setVideoURL(resultSet.getString("video_url"));
//                item.setEpisodeID(null);
//                String origin = resultSet.getString("origin");
//                String link = resultSet.getString("link");
//                String author = resultSet.getString("author");
//                if (origin == null) {
//                    origin = "";
//                }
//                if (link == null) {
//                    link = "";
//                }
//                if (author == null || author.equals("Автор не указан") || author.equals("не указан")) {
//                    author = "";
//                } else {
//                    author = "Фото: " + author + " ";
//                }
//                item.setCopyright(author + "<a href='" + link + "'>" + origin + "</a>");
//                item.setCopyrightSrc(author + origin);
//                item.setRushHourNews(resultSet.getBoolean("lightning"));
//                item.setTopListNews(resultSet.getBoolean("main_top"));
//                item.setHasGallery(resultSet.getBoolean("with_gallery"));
//                item.setPublished(resultSet.getBoolean("published"));
//                item.setOnMainPagePosition(resultSet.getInt("main_center"));
//                news.add(item);
//            }
//        } catch (SQLException sqlex) {
//                    logger.error("Can't parse items from result set: " + sqlex);
//                }
//
//        return news;
//    }
//
//    private void saveLastNews(ArrayList<NewsItem> news) {
//
//                query = "INSERT INTO news (id, date, title, shortText, shortTextSrc, text, textSrc, "
//                    + "                  imageID, categoryID, serieID, videoID, episodeID, "
//                    + "                  copyright, copyrightSrc, rushHourNews, topListNews, "
//                    + "                  hasGallery, published, onMainPagePosition, videoDuration) "
//                    + "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) "
//                    + "ON DUPLICATE KEY UPDATE "
//                    + "       id = VALUES(id), date = VALUES(date), title = VALUES(title), "
//                    + "       shortText = VALUES(shortText), shortTextSrc = VALUES(shortTextSrc), "
//                    + "       text = VALUES(text), textSrc = VALUES(textSrc), imageID = "
//                    + "       VALUES(imageID), categoryID = VALUES(categoryID), serieID = VALUES("
//                    + "       serieID), videoID = VALUES(videoID), episodeID = VALUES(episodeID), "
//                    + "       copyright = VALUES(copyright), copyrightSrc = VALUES(copyrightSrc), "
//                    + "       rushHourNews = VALUES(rushHourNews), topListNews = VALUES(topListNews), "
//                    + "       hasGallery = VALUES(hasGallery), published = VALUES(published), "
//                    + "       onMainPagePosition = VALUES(onMainPagePosition), videoDuration = "
//                    + "       VALUES(videoDuration)";
//
//                DBMessanger messanger = new DBMessanger("m24api");
//        Connection connection = messanger.getConnection();
//
//        for (NewsItem item : news) {
//            try {
//                CallableStatement preparedCall = connection.prepareCall(query);
//                preparedCall.setInt(1, item.getId());
//                preparedCall.setTimestamp(2, item.getDate());
//                preparedCall.setString(3, item.getTitle());
//                preparedCall.setString(4, item.getShortText());
//                preparedCall.setString(5, item.getShortTextSrc());
//                preparedCall.setString(6, item.getText());
//                preparedCall.setString(7, item.getTextSrc());
//                preparedCall.setInt(8, item.getImageID());
//                preparedCall.setInt(9, item.getCategoryID());
//                preparedCall.setInt(10, 0);
//                preparedCall.setInt(11, item.getVideoID());
//                preparedCall.setInt(12, 0);
//                preparedCall.setString(13, item.getCopyright());
//                preparedCall.setString(14, item.getCopyrightSrc());
//                preparedCall.setBoolean(15, item.getRushHourNews());
//                preparedCall.setBoolean(16, item.getTopListNews());
//                preparedCall.setBoolean(17, item.getHasGallery());
//                preparedCall.setBoolean(18, item.getPublished());
//                preparedCall.setInt(19, item.getOnMainPagePosition());
//                String duration = "00:00:00.00";
//                if (item.getVideoID() != 0) {
//                    duration = getDuration(item.getVideoURL());
//                }
//                preparedCall.setString(20, duration);
//                preparedCall.execute();
//            } catch (SQLException sqlex) {
//                logger.error("Can't save last news: " + sqlex.toString());
//            }
//        }
//
//        messanger.closeConnection();
//    }
//
//    /**
//     * Get duration of file using system script and ffmpeg library.
//     */
//    private String getDuration(String videoURL) {
//                String duration = "";
//        String command = "/bin/bash /etc/scripts/get_video_duration.sh ";
//
//        ArrayList output;
//        try {
//            if(!videoURL.contains(DEFAULT_VIDEO_URL)){
//                videoURL = DEFAULT_VIDEO_URL.concat(videoURL);
//            }
//            String protocol = "http";
//            String domain = videoURL.substring(0, videoURL.indexOf("/"));
//            String file = videoURL.substring(videoURL.indexOf("/"), videoURL.length());
//            URI uri = new URI(protocol, domain, file, null);
//            output = ShellExecutor.executeCommand(command + uri.toASCIIString());
//            for (Object line : output) {
//                duration = duration.concat((String) line);
//            }
//            duration = duration.substring(0, duration.length() - 1);
//        } catch (IOException | URISyntaxException | IndexOutOfBoundsException ex) {
//                    duration = "00:00:00.00";
//                    logger.error("Can't get duration of file with url " + videoURL + ": " + ex);
//                }
//        return duration;
//    }
//
//    private void saveNewsTags(HashMap<Integer, Set<Integer>> newsTags)
//            throws SQLException {
//
//                DBMessanger messanger = new DBMessanger("m24api");
//        Connection connection = messanger.getConnection();
//
//        String dropQuery = "DELETE FROM news_tags WHERE news_id = ?";
//        String addQuery = "INSERT IGNORE INTO news_tags(news_id, tag_id) "
//                    + "VALUES (?, ?)";
//
//        PreparedStatement dropTags = null;
//        PreparedStatement addTags = null;
//
//        try {
//            //we need transaction to rollback records if error occurs
//            connection.setAutoCommit(false);
//            dropTags = connection.prepareStatement(dropQuery);
//            addTags = connection.prepareStatement(addQuery);
//
//            Set keys = newsTags.keySet();
//            Iterator<Integer> it = keys.iterator();
//
//            while (it.hasNext()) {
//                Integer newsId = it.next();
//                //drop all existing tags for this news_id
//                dropTags.setInt(1, newsId);
//                dropTags.executeUpdate();
//                //add new tags
//                Set<Integer> tags = newsTags.get(newsId);
//                addTags.setInt(1, newsId);
//                for (Integer tagId : tags) {
//                    addTags.setInt(2, tagId);
//                    addTags.executeUpdate();
//                }
//                connection.commit();
//            }
//        } catch (SQLException sqlex) {
//                    try {
//                        if (connection != null) {
//                            connection.rollback();
//                        }
//                        logger.error("Can't save news tags. Rollback executed. Error: " + sqlex);
//                    } catch (SQLException rlex) {
//                        logger.error("Can't save news tags. Can't rollback changes. Error: " + rlex);
//                    }
//        } finally {
//                    if (dropTags != null) {
//                        dropTags.close();
//                    }
//                    if (addTags != null) {
//                        addTags.close();
//                    }
//                    if (connection != null) {
//                        connection.close();
//                    }
//                }
//    }
//
//    /**
//     *
//     * @param news
//     */
//    public void updateActualNews(int[] news) {
//                dropActualNews();
//                saveActualNews(news);
//            }
//
//    private void dropActualNews() {
//            query = "DELETE FROM actual_news";
//        DBMessanger messanger = new DBMessanger("m24api");
//        messanger.doUpdate(query);
//        messanger.closeConnection();
//    }
//
//    /**
//     * Save id's of actual news to separate table for fast access.
//     *
//     * @param news array of news id
//     */
//    private void saveActualNews(int[] news) {
//
//                DBMessanger messanger = new DBMessanger("m24api");
//        Connection connection = messanger.getConnection();
//
//        query = "INSERT INTO actual_news(id, news_id) VALUES (?,?) "
//            + "ON DUPLICATE KEY "
//            + "UPDATE id = VALUES(id), news_id = VALUES(news_id)";
//
//        try {
//            PreparedStatement addStatement = connection.prepareCall(query);
//            for (int i = 0; i < news.length; i++) {
//                try {
//                    addStatement.setInt(1, i);
//                    addStatement.setInt(2, news[i]);
//                    addStatement.execute();
//                } catch (SQLException sqlex) {
//                    logger.error("Error while adding actual news: " + sqlex);
//                }
//            }
//        } catch (SQLException ex) {
//                    logger.error("Can't save actual news: " + ex.toString());
//                } finally {
//                    messanger.closeConnection();
//                }
//    }
//
//    /**
//     * Get single news by id.
//     *
//     * @param newsID
//     * @return NewsItem instance with specified id.
//     */
//    public ArrayList<NewsItem> getNewsById(Integer newsID) {
//
//                ArrayList<NewsItem> newsItem;
//
//        query = "SELECT    n.id, n.created_at, n.published_at, n.advert, n.text, "
//            + "          n.title, in.image_id, t.id AS rubric_id, nv.video_id, "
//            + "          c.origin, c.link, n.lightning, n.main_top, (n.status = 'active') AS published, "
//            + "          n.main_center "
//            + "FROM      news n "
//            + "LEFT JOIN image_news `in` ON in.news_id = n.id "
//            + "LEFT JOIN news_tag nt ON nt.news_id = n.id "
//            + "LEFT JOIN tags t ON t.id = nt.tag_id "
//            + "LEFT JOIN news_video nv ON nv.news_id = n.id "
//            + "LEFT JOIN copyright_news cn ON cn.news_id = n.id "
//            + "LEFT JOIN copyrights c ON cn.copyright_id = c.id "
//            + "WHERE     n.id = " + newsID + " AND t.type = 3";
//
//        DBMessanger messanger = new DBMessanger("mir24");
//        ResultSet resultSet = messanger.doQuery(query);
//
//        newsItem = parseItemsFromResultSet(resultSet);
//
//        messanger.closeConnection();
//
//        return newsItem;
//    }
//
//    private void setUpdateComplete(Boolean status) {
//                query = "UPDATE status "
//                    + "SET    int_value = " + status + " "
//                    + "WHERE  variable_name = 'UPDATE_COMPLETE'";
//                DBMessanger messanger = new DBMessanger("m24api");
//        messanger.doUpdate(query);
//        messanger.closeConnection();
//    }
//
//}


//private int getLastCountryLinkId(){
//
//int lastID = 0;
//
//    DBMessanger messanger = new DBMessanger("m24api");
//
//    query = "SELECT MAX(news_id) AS last_id FROM news_country";
//
//    ResultSet resultSet = messanger.doQuery(query);
//
//    try {
//        if(resultSet.next()){
//            lastID = resultSet.getInt("last_id");
//        }
//    }
//    catch(SQLException sqlex){
//    logger.error("Can't get last country link id: " + sqlex);
//}
//    finally {
//    messanger.closeConnection();
//}
//
//    return lastID;
//  }

//  Закоментировано  т.к. данные о размерах изображений берутся из images.properties
//  В дальнейшем продумать парсинг из базы данных. Может быть через distinc или group by

/*  public ArrayList<ImageType> getImageTypes() { //private

    ArrayList<ImageType> types       = new ArrayList<ImageType>();
    PGDBMessanger        pgMessanger = new PGDBMessanger();

    query = "SELECT name, width, height " +
            "FROM   imageresize";

    ResultSet resultSet = pgMessanger.doQuery(query);

    try {
      while(resultSet.next()){
        ImageType imageType = new ImageType();
        imageType.setAlias(resultSet.getString("name"));
        imageType.setWidth(resultSet.getInt("width"));
        imageType.setHeight(resultSet.getInt("height"));
        types.add(imageType);
        if(types.size()>10){
          saveImageTypes(types);
          types.clear();
        }
      }
    }
    catch(SQLException sqlex){
      logger.error("Can't get image types: " + sqlex.toString());
    }
    finally {
      pgMessanger.closeConnection();
    }

    return types;
  }

  private void saveImageTypes(ArrayList<ImageType> types) {

    DBMessanger messanger  = new DBMessanger();
    Connection  connection = messanger.getConnection();

    for(ImageType imageType:types){
      try {
        query = "INSERT INTO image_types (alias, size) " +
                "VALUES (?, ?) " +
                "ON DUPLICATE KEY UPDATE " +
                "   alias = VALUES(alias), size = VALUES(size)";
        CallableStatement preparedCall = connection.prepareCall(query);
        preparedCall.setString(1, imageType.getAlias());
        preparedCall.setString(2, imageType.getWidth() + "x" + imageType.getHeight());
        preparedCall.execute();
      }
      catch(SQLException sqlex){
        logger.error("Error while updating image types: " + sqlex.toString());
      }
    }

    messanger.closeConnection();
  }
*/

//  public void dropTheBase() {
//DBMessanger messanger = new DBMessanger("m24api");
//    messanger.doUpdate("CALL dropOldInfo");
//    messanger.closeConnection();
//  }
