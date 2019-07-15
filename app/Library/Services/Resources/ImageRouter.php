<?php


namespace App\Library\Services\Resources;


use App\Crops;
use App\Exceptions\RestrictedOldException;
use App\News;

use App\Photos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageRouter implements InterfaceRouter
{
    public function getFromMir($imageId)
    {

        $query = "SELECT  image_id , src, type , updated_at "
            . "FROM     crops "
            . "WHERE    image_id = '$imageId' "
            . "and  "
            . " (type = 'in_promo' or type = 'rubric_main' or type = 'rubric_list' or type = 'inner' or type = 'detail_crop') ";

        $result = DB::connection('mir24')->select($query);

        return $result;
    }


    public function getImage($imageId, $type)
    {
        return Crops::getImage($imageId, $type)->first();
    }

    public function getSrc($imageId, $type)
    {
        if ($ph = Photos::where('image_id', $imageId)->first()) {
            return config('api_images.image_root') . $ph->link;
        }

        $crop = $this->getImage($imageId, $type);

        if ($crop) {
            return config('api_images.image_root') . $crop->src;
        }

        $data = $this->getFromMir($imageId);

        $news = News::where('imageID', $imageId)->first();

        if (!$news) {
            Log::error("Not found news for $imageId");
            throw new RestrictedOldException('','Data is empty or invalid');
        }

        foreach ($data as $item) {
            Crops::updateOrCreate(
                [
                    'news_id' => $news->id,
                    'itemType' => $item->type
                ],
                ['src' => $item->src]);
        }

        $crop = $this->getImage($imageId, $type);

        if ($crop) {
            return config('api_images.image_root') . $crop->src;
        }

        Log::error("Not found 'crops' for image with id={$imageId}");

        throw new RestrictedOldException('','Data is empty or invalid');
    }


    function getResult(array $params): string
    {
        if (!isset($params['id']) || !isset($params['type'])) {
            Log::error('Need use image and type params');
            throw new RestrictedOldException('','Data is empty or invalid');
        }
        return $this->getSrc($params['id'], $params['type']);
    }
}