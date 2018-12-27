<?php

namespace App;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';


    public function scopeGetText(Builder $query, $newsId): Builder
    {
        return $query->where('id', $newsId); # TODO
    }

    public function scopeGetList(Builder $query, array $where): Builder
    {
        return $query->where($where); # TODO
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

///**
// * Get news from api database based on NewsOptions filter.
// *
// * @param options filter options for news to select. All options are
// * optional.
// * @return array of NewsItem objects. If limit and page not specified - uses
// * it's default values.
// */
//public ArrayList<NewsItem> getNewsList(NewsOptions options) {
//
//    ArrayList<NewsItem> news = new ArrayList<>();
//        query = "SELECT n.id id, date, shortText, shortTextSrc, textSrc, title, imageID, "
//            + "       categoryID, serieID, videoID, episodeID, copyright, copyrightSrc, "
//            + "       rushHourNews, topListNews, hasGallery, videoDuration, (SELECT GROUP_CONCAT("
//            + "       tag_id SEPARATOR ',') FROM news_tags WHERE news_id = n.id) AS tags, "
//            + "       (SELECT GROUP_CONCAT(country_id SEPARATOR ',') FROM news_country "
//            + "       WHERE news_id = n.id) AS country "
//            + "FROM   news n ";
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
//            if (options.getCategory() == null) {
//                query = query.concat("LEFT JOIN categories c "
//                        + "ON c.id = n.categoryID "
//                        + "AND c.show = true ");
//            }
//            if (options.getTags() != null && options.getLastNews() == false
//                && !options.getTags().isEmpty()) {
//                ArrayList<Integer> tags = options.getTags();
//                query = query.concat("RIGHT JOIN news_tags nt "
//                        + "ON n.id = nt.news_id "
//                        + "AND nt.tag_id IN (");
//                for (Integer tagID : tags) {
//                    query = query.concat(tagID + ", ");
//                }
//                query = query.substring(0, query.length() - 2).concat(") ");
//            }
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
//            if (options.getActual()) {
//                query = query.concat("RIGHT JOIN actual_news an ON n.id = an.news_id ");
//            }
//            query = query.concat("WHERE ");
//            if (options.getCategory() != null) {
//                query = query.concat("categoryID = " + options.getCategory() + " AND ");
//            }
//            if (options.getOnlyVideo()) {
//                query = query.concat("videoID != 0 AND ");
//            }
//            if (options.getOnlyWithGallery()) {
//                query = query.concat("hasGallery = 1 AND ");
//            }
//            if (options.getPage() == null) {
//                options.setPage(1);
//            }
//            if (options.getNewsID() != null) {
//                query = query.concat("n.id = " + options.getNewsID() + " AND ");
//            }
//            if (options.getIgnoreId() != null && options.getIgnoreId().length > 0) {
//                query = query.concat("n.id NOT IN ("
//                        + ArrayUtils.Join(options.getIgnoreId(), ",")) + ") AND ";
//            }
//            query = query.concat("n.published = true ");
//            query += options.getActual() ? "ORDER BY an.id ASC " : "ORDER BY id DESC ";
//            query = query.concat("LIMIT " + options.getLimit() + " OFFSET "
//                    + (options.getLimit() * (options.getPage() - 1)));
//        }
//
//        DBMessanger messanger = new DBMessanger("m24api");
//        ResultSet set = messanger.doQuery(query);
//
//        try {
//            while (set.next()) {
//                NewsItem item = new NewsItem();
//                item.setId(set.getInt("id"));
//                item.setDate(set.getTimestamp("date"));
//                item.setShortText(set.getString("shortText"));
//                item.setShortTextSrc(set.getString("shortTextSrc"));
//                item.setTitle(set.getString("title"));
//                item.setImageID(set.getInt("imageID"));
//                item.setCategoryID(set.getInt("categoryID"));
//                item.setSerieID(set.getInt("serieID"));
//                item.setVideoID(set.getInt("videoID"));
//                item.setEpisodeID(set.getInt("episodeID"));
//                item.setCopyright(set.getString("copyright"));
//                item.setCopyrightSrc(set.getString("copyrightSrc"));
//                item.setRushHourNews(set.getBoolean("rushHourNews"));
//                item.setTopListNews(set.getBoolean("topListNews"));
//                item.setVideoDuration(set.getString("videoDuration"));
//                if (options.getOnlyWithGallery()) {
//                    item.setHasGallery(Boolean.TRUE);
//                } else {
//                    item.setHasGallery(set.getBoolean("hasGallery"));
//                }
//                String tmp = set.getString("tags");
//                ArrayList<Integer> tags = new ArrayList();
//                if (tmp != null) {
//                    while (tmp.length() > 0) {
//                        if (tmp.contains(",")) {
//                            tags.add(Integer.parseInt(tmp.substring(0, tmp.indexOf(","))));
//                            tmp = tmp.substring(tmp.indexOf(",") + 1, tmp.length());
//                        } else {
//                            tags.add(Integer.parseInt(tmp));
//                            tmp = "";
//                        }
//                    }
//                }
//                item.setTags(tags);
//                tmp = set.getString("country");
//                ArrayList<Integer> countries = new ArrayList<>();
//                if (tmp != null) {
//                    while (tmp.length() > 0) {
//                        if (tmp.contains(",")) {
//                            countries.add(Integer.parseInt(tmp.substring(0, tmp.indexOf(","))));
//                            tmp = tmp.substring(tmp.indexOf(",") + 1, tmp.length());
//                        } else {
//                            countries.add(Integer.parseInt(tmp));
//                            tmp = "";
//                        }
//                    }
//                } else {
//                    countries.add(4453);
//                }
//                item.setCountry(countries);
//                news.add(item);
//            }
//        } catch (SQLException ex) {
//        logger.error("Can't get newslist: " + ex);
//    } finally {
//        messanger.closeConnection();
//    }
//        if (options.getActual() && options.getPage() == 1
//            && news.size() < SiteConfig.PROMO_NEWS_COUNT) {
//            options.setActual(Boolean.FALSE);
//            int[] ignoreId = new int[news.size()];
//            for (int i = 0; i < ignoreId.length; i++) {
//                ignoreId[i] = news.get(i).getId();
//            }
//            options.setIgnoreId(ignoreId);
//            options.setLimit(SiteConfig.PROMO_NEWS_COUNT - news.size());
//            news.addAll(getNewsList(options));
//        }
//        return news;
//    }
