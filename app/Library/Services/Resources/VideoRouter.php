<?php


namespace App\Library\Services\Resources;


use App\Exceptions\RestrictedOldException;
use App\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VideoRouter implements InterfaceRouter
{
    public function getVideo($videoID)
    {
        return News::getVideoUrl($videoID)->first();
    }

    public function getFromMir($videoID)
    {
        $query = "SELECT   url "
            . "FROM     videos "
            . "WHERE    id = '{$videoID}'"
            . "ORDER BY updated_at DESC "
            . "LIMIT    1";

        $result = DB::connection('mir24')->select($query);

        return $result[0]->url ?? null;
    }

    public function getUrl($videoID)
    {
        $news = $this->getVideo($videoID);

        if (!$news) {
            Log::error("Not found news with video id {$videoID}");
            throw new RestrictedOldException('','Data is empty or invalid');
        }

        if ($news->url) {
            return $news->url;
        }

        $url = $this->getFromMir($videoID);

        if ($url) {
            $news->video_url = config('api_images.video_root') . $url;
            $news->save();

            return config('api_images.video_root') . $url;
        }

        throw new RestrictedOldException('','Data is empty or invalid');
    }

    function getResult(array $params): string
    {
        if (!isset($params['videoID'])) {
            Log::error('Need use videoID params');
            throw new RestrictedOldException('','Data is empty or invalid');
        }

        return $this->getUrl($params['videoID']);
    }
}