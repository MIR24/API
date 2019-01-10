<?php

namespace App\Library\Services\Commands;


use App\ActualNews;
use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Services\Cache\NewsCaching;
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
            ->setContent($news)
            ->setMessage(sprintf("Total of %d news parsed.", count($news)))
            ->setStatus(200);
    }

    private function selectFromCache(NewsOption $options)
    {
        $news = [];

        if (!$options->isLastNews()) {
            return $news;
        }

        if ($options->getPage() == 1 && $options->getLimit() == $options::LIMIT_DEFAULT && $options->getCountryID() == null) {
            if ($options->isOnlyVideo()) {
                $news = NewsCaching::getLastNewsWithVideo();
            } elseif ($options->isOnlyWithGallery()) {
                $news = NewsCaching::getLastNewsWithGallery();
            } else {
                $news = NewsCaching::getLastNews();
            }
        }

//        if (news == null || news.isEmpty() && !options.getOnlyVideo() && !options.getOnlyWithGallery() && options.getCountryID() != null)
//            TODO searchTable;
//        }

        return $news;
    }

    private function selectFromDb(NewsOption $newsOption)
    {
        $news = News::GetList($newsOption)->get()->all();

        //TODO если в запросе ищут tags???
        //Например:
        //{"request":"newslist",
        //"options":
        //{"limit":"10",
        // "page":"1",
        // "tags":[10089538]},
        //"token":"token_id"}
        foreach ($news as $newsItem) {
            News::postprocessingOfGetList($newsItem);
        };

        // Если в актуальных новостях не хватает новостей, то дополнить результат из обычных новостей
        if ($newsOption->isActual() && $newsOption->getPage() == 1 && count($news) < ActualNews::PROMO_NEWS_COUNT) {
            $newsOption->setActual(false);

            $ignoreId=[];
            foreach ($news as $newsItem) {
                $ignoreId[]=$newsItem->id;
            }
            $newsOption->setIgnoreId($ignoreId);
            $newsOption->setLimit(ActualNews::PROMO_NEWS_COUNT - count($news));
            $news = array_merge($news, $this->getContextArray($newsOption));
        }

        return $news;
    }


    private function getContextArray(NewsOption $option){
        $news = News::GetList($option)->get();
        foreach ($news as $newsItem) {
            News::postprocessingOfGetList($newsItem);
        };
        return $news->toArray();

    }

}
//    if (options.getLastNews() == true) {
//        if ((options.getPage() == null
//                || options.getPage() == 1)
//                && options.getLimit() == 10
//                && options.getCountryID() == null) {
//            if (options.getOnlyVideo() == true) {
//                news = (ArrayList<NewsItem>) getServletContext().getAttribute("newsWithVideo");
//            } else if (options.getOnlyWithGallery() == true) {
//                news = (ArrayList<NewsItem>) getServletContext().getAttribute("newsWithGallery");
//            } else {
//                news = (ArrayList<NewsItem>) getServletContext().getAttribute("lastNews");
//            }
//        }
//        if (news == null || news.isEmpty()) {
//            if (!options.getOnlyVideo()
//                    && !options.getOnlyWithGallery()
//                    && options.getCountryID() != null) {
//                MultiKeyMap searchTable
//                        = (MultiKeyMap) getServletContext().getAttribute("searchTable");
//                if (searchTable == null) {
//                    searchTable = getter.getSearchTable();
//                }
//                ArrayList<Integer> preSearch = new ArrayList<Integer>();
//                for (Category category : categories) {
//                    ArrayList<Integer> arr = (ArrayList<Integer>) searchTable.get(options.getCountryID(),
//                          category.getId());
//                  if (arr != null) {
//                      for (int i = ((options.getPage() - 1) * options.getLimit());
//                              i < options.getLimit() * options.getPage();
//                              i++) {
//                          try {
//                              preSearch.add(arr.get(i));
//                          } catch (IndexOutOfBoundsException iob) {
//                              break;
//                          }
//                       }
//                    }
//                }
//                if (!preSearch.isEmpty()) {
//                    options.setPreSearch(preSearch);
//                    news = getter.getNewsList(options);
//                } else {
//                    news = new ArrayList<>();
//                }
//            } else {
//                news = getter.getLastNews(options);
//            }
//        }
//    } else {
//        news = getter.getNewsList(options);
//        serverResponse.setNewsCount(getter.getNewsCount(options.getCategory()));
//    }
