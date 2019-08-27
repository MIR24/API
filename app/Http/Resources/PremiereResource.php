<?php


namespace App\Http\Resources;


use App\Http\AppJsonResource;

class PremiereResource extends AppJsonResource
{
    public function toArray($request){
        return [
            'title'=>$this->title,
            'description'=>$this->description,
        ];
    }
}
