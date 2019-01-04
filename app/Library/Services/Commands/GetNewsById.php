<?php

namespace App\Library\Services\Commands;


use App\Exceptions\InvalidClientOldException;
use App\Exceptions\NotFoundOldException;
use App\Library\Components\EloquentOptions\NewsOption;
use App\Library\Services\ResultOfCommand;
use App\News;


class GetNewsById implements CommandInterface
{
    private const OPERATION = "newsById";

    /**
     * @param array $options
     * @return ResultOfCommand
     * @throws InvalidClientOldException
     * @throws NotFoundOldException
     */
    public function handle(array $options): ResultOfCommand
    {
        $newsOption = (new NewsOption())
            ->setActual(false)
            ->setLastNews(false)
            ->setOnlyVideo(false)
            ->setOnlyWithGallery(false)
            ->setPage(1)
            ->setLimit(1);

        if (isset($options["newsID"])) {
            $newsOption->setNewsID($options["newsID"]);
        } else {
            throw new InvalidClientOldException($this::OPERATION, "Required option: newsID");
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
