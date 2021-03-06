<?php

namespace App\Http\Resources;


use App\Http\AppJsonResource;


class NewsItemResource extends AppJsonResource
{
    use NewsItemTrait;

    public function toArray($request)
    {
        $result = $this->getCommonResult();

        return $this->removeKeysWhereValueIsNull($result);
    }
}
