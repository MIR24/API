<?php

namespace App\Http;


use Illuminate\Http\Resources\Json\JsonResource;


abstract class AppJsonResource extends JsonResource
{
    protected function removeKeysWhereValueIsNull($result)
    {
        foreach ($result as $k => $v) {
            if ($v === null) {
                unset($result[$k]);
            }
        }

        return $result;
    }
}
