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
            "videoID" => $this->videoID,
            "copyright" => $this->copyright,
            "copyrightSrc" => $this->copyrightSrc,
            "topListNews" => boolval($this->topListNews),
            "hasGallery" => boolval($this->hasGallery),
            "videoDuration" => $this->videoDuration,
        ];

        if ($result["videoDuration"] == "00:00:00.00") {
            $result["videoDuration"] = null;
        }

        return $result;
    }
}
