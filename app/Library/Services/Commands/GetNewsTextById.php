<?php

namespace App\Library\Services\Command;


use App\Exceptions\InvalidClientOldException;
use App\Exceptions\NotFoundOldException;
use App\Library\Services\ResultOfCommand;
use App\News;


class GetNewsTextById implements CommandInterface
{
    private const OPERATION = "text";

    /**
     * @param array $options
     * @return ResultOfCommand
     * @throws InvalidClientOldException
     * @throws NotFoundOldException
     */
    public function handle(array $options): ResultOfCommand
    {
        $newsId = null;
        if (isset($options["newsID"])) {
            $newsId = $options["newsID"];
        } else {
            throw new InvalidClientOldException($this::OPERATION, "Required option: newsID");
        }

        $newsItem = News::GetNewsText($newsId)->first();
        if ($newsItem === null) {
            throw new NotFoundOldException($this::OPERATION);
        }
        $newsItem = News::replaceText($newsItem);

        return (new ResultOfCommand())
            ->setOperation($this::OPERATION)
            ->setContent($newsItem)
            ->setMessage(sprintf("Text for news with id %d.", $newsId))
            ->setStatus(200);
    }
}
