<?php

namespace App\Library\Services\Command;


use App\Library\Services\ResultOfCommand;
use App\News;
use Illuminate\Database\Eloquent\Collection;

class GetNewsById implements CommandInterface
{
    public function handle(array $options): ResultOfCommand
    {
        $newsId = null;
        $newsItem = null;

        if (isset($options["newsID"])) {
            $newsId = $options["newsID"];
        }

        $where = [
            'id' => $newsId,
// TODO        options.setActual(Boolean.FALSE);
//        options.setLastNews(Boolean.FALSE);
//        options.setOnlyVideo(Boolean.FALSE);
//        options.setOnlyWithGallery(Boolean.FALSE);
//        options.setPage(1);
//        options.setLimit(1);
        ];

        $newsItem = News::GetList($where)->get()->get(0);
        // TODO item.setNewsText(getNewsText(newsID)); # TODO News::GetText

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
