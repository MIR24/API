<?php

namespace App\Http\Resources;


use App\Http\AppJsonResource;


class NewsDetailItemResource extends AppJsonResource
{
    use NewsItemTrait;

    public function toArray($request)
    {
        $result = $this->getCommonResult();

        $result["textWithTags"] = $this->newsText['textWithTags'];
        $result["textSource"] = $this->newsText['textSource'];

        return $this->removeKeysWhereValueIsNull($result);
    }
}
