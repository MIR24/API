<?php

namespace App\Library\Services\Command;


use App\Exceptions\NotFoundOldException;
use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Components\NewsTextConverter;
use App\Library\Services\ResultOfCommand;
use App\News;
use Illuminate\Database\Eloquent\Collection;

class GetNewsById implements CommandInterface
{
    private const OPERATION = "newsById";

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

        $newsItem = News::GetList($newsOption)->first();
        if ($newsItem === null) {
            throw new NotFoundOldException($this::OPERATION);
        }

        $newsItem = News::postprocessingOfGetList($newsItem);

        // TODO item.setNewsText(getNewsText(newsID)); # TODO News::GetText
        $newsItem->newsText = (new NewsTextConverter())
            ->setText($newsItem->newsText)
//            ->cutGalleryTags() TODO
            ->changeTextLinks()
            ->getText();


        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent($newsItem)
            ->setMessage("News by id parsed correct.")
            ->setStatus(200);
    }
}
