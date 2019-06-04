<?php


namespace App\Library\Services\Resources;


use App\Crops;
use App\News;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageRouter
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
        if (Cache::has($type . $imageId)) {
            return Cache::get($type . $imageId);
        }

        $crop = $this->getImage($imageId, $type);

        if ($crop) {
            Cache::put($type . $imageId, config('api_images.image_root') . $crop->src, config('cache.images_url_cache_time'));
            return config('api_images.image_root') . $crop->src;
        }

        $data = $this->getFromMir($imageId);

        $news = News::where('imageID', $imageId)->first();

        if (!$news) {
            Log::error("Not found news for $imageId");
            abort(404);
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
            Cache::put($type . $imageId, config('api_images.image_root') . $crop->src, config('cache.images_url_cache_time'));
            return config('api_images.image_root') . $crop->src;
        }

        Log::error("Not found 'crops' for image with id={$imageId}");

        abort(404);
    }


}