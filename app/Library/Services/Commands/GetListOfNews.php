<?php

namespace App\Library\Services\Command;


use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Services\ResultOfCommand;
use App\News;


class GetListOfNews implements CommandInterface
{
    private const OPERATION = "newslist";

    public function handle(array $options): ResultOfCommand
    {
        $newsOption = (new NewsOption())->initFromArray($options);

        // TODO if (options.getLastNews() == true) ...

        $news = News::GetList($newsOption)->get()->all();
        foreach ($news as $newsItem) {
            News::postprocessingOfGetList($newsItem);
        }

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent($news)
            ->setMessage(sprintf("Total of %d news parsed.", count($news)))
            ->setStatus(200);
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
