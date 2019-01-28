<?php

namespace App\Http\Resources;


use App\Http\AppJsonResource;


class NewsTextItemResource extends AppJsonResource
{
    public function toArray($request)
    {
        $result = [
            "textWithTags" => $this->newsText['textWithTags'],
            "textSource" => $this->newsText['textSource'],
        ];

        return $result;
    }
}
