<?php

namespace App\Library\Services\Command;


use App\Exceptions\NotFoundOldException;
use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Services\ResultOfCommand;
use App\News;


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
        $newsItem = News::replaceText($newsItem);

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent($newsItem)
            ->setMessage("News by id parsed correct.")
            ->setStatus(200);
    }
}
