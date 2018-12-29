<?php

namespace App\Library\Services\Command;


use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Components\NewsTextConverter;
use App\Library\Services\ResultOfCommand;
use App\News;
use Illuminate\Database\Eloquent\Collection;

class GetNewsById implements CommandInterface
{
    public function handle(array $options): ResultOfCommand
    {
        $newsItem = null;

        $newsOption = (new NewsOption())
            ->setActual(false)
            ->setLastNews(false)
            ->setOnlyVideo(false)
            ->setOnlyWithGallery(false)
            ->setPage(1)
            ->setLimit(1);

        if (isset($options["newsID"])) {
            $newsOption->setNewsID($options["newsID"]);
        }

        $newsItem = News::GetList($newsOption)->get()->get(0);

        $newsItem = News::postprocessingOfGetList($newsItem);

        // TODO item.setNewsText(getNewsText(newsID)); # TODO News::GetText
        $newsItem->newsText = (new NewsTextConverter())
            ->setText($newsItem->newsText)
            ->changeTextLinks()
            ->getText();

        if ($newsItem === null) {
            # TODO 404?
        }

        return (new ResultOfCommand())
            ->setOperation('newsById')
            ->setContent($newsItem)
            ->setMessage("News by id parsed correct.")
            ->setStatus(200);
    }
}
