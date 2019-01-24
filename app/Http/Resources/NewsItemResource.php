<?php

namespace App\Http\Resources;


use App\Http\AppJsonResource;


class NewsItemResource extends AppJsonResource
{
    public function toArray($request)
    {
        $result = [
            "id" => $this->id,
            "date" => (new \DateTime($this->date))->format("M d, Y h:m:s A"),
            "shortText" => $this->shortText,
            "shortTextSrc" => $this->shortTextSrc,
            "title" => $this->title,
            "imageID" => $this->imageID,
            "categoryID" => $this->categoryID,
            "serieID" => $this->serieID,
            "videoID" => $this->videoID,
            "episodeID" => $this->episodeID,
            "copyright" => $this->copyright,
            "copyrightSrc" => $this->copyrightSrc,
            "rushHourNews" => boolval($this->rushHourNews),
            "topListNews" => boolval($this->topListNews),
            "hasGallery" => boolval($this->hasGallery),
            "videoDuration" => $this->videoDuration,
            "tags" => $this->tags,
            "country" => $this->country,
            "newsText" => $this->newsText
//            "newsText" => [
//                "textWithTags" => $this->newsText->textWithTags,
//                "textSource" => $this->newsText->textSource,
//                "link" => $this->newsText->link
//            ]
        ];

        return $this->removeKeysWhereValueIsNull($result);
    }
}
