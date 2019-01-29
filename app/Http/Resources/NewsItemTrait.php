<?php

namespace App\Http\Resources;


trait NewsItemTrait
{
    protected function getCommonResult()
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
        ];

        return $result;
    }
}
