<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.2.19
 * Time: 9.15
 */

namespace App\Library\Services\Import;


use App\Episode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Log;

class SmartTvVideo
{
    /**
     * @var array
     */
    private $params;

    /**
     * SmartTvVideo constructor.
     *
     * @param $params array
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * The method return duration video in minutes
     * used @see FFMpeg
     * If file was not found info about it will write into log
     *
     * @param $file string url or file path to video
     * @return int 0 if video was not found else  duration video iin minutes
     */
    private function getDuration($file)
    {
        try {
            $ffmpeg = FFMpeg::create();

            $duration = $ffmpeg->open($file)
                ->getStreams()
                ->videos()
                ->first()
                ->get('duration');

        } catch (\Exception $ex) {
            Log::info("Not found video : {$file}");
            return 0;
        }

        return intval($duration);
    }


    /**
     * The method set video duration for current Episode
     * If video was not found info about it will write into log
     *
     * @param Episode $episode
     */
    public function fixVideoEpisode(Episode $episode)
    {
        $url = $episode->url;

        $duration = $this->getDuration($url);

        if ($duration == 0) {
            $url = $this->getUrlVideo($episode->id, "video.mp4");

            $duration = $this->getDuration($url);
            if ($duration == 0) {
                Log::error("Not found video for episode with ID : {$episode->id}");
                try {
                    $episode->delete();
                } catch (\Exception $ex) {
                    Log::error("Can not delete episode with ID: {$episode->id}");
                }
            } else {
                $episode->url = $url;
                $episode->time_end = $this->getEndTime($episode, $duration);

                $episode->save();
            }

        } else {
            $episode->time_end = $this->getEndTime($episode, $duration);

            $episode->save();
        }
    }

    /**
     * The method return end for video using video duration
     *
     * @param Episode $episode
     * @param $duration int video duration in minutes
     * @return string date with format (Y-m-d H:i:s)
     */
    private function getEndTime(Episode $episode, $duration)
    {
        try {
            return (new \DateTime($episode->time_begin))
                ->add(new \DateInterval("PT{$duration}S"))
                ->format('Y-m-d H:i:s');
        } catch (\Exception $ex) {
            Log::error("DataTime error({$ex->getMessage()}) when set episode with ID {$episode->id}");
            return $episode->time_begin;
        }

    }

    /**
     * @param $id int ID for @see Episode
     * @param $url string address for video in database
     * @return string full url for video
     */
    private function getUrlVideo($id, $url)
    {
        if ($url == 'video.mp4')
            return sprintf($this->params['video_pattern_1'], $id, $url);
        else
            return sprintf($this->params['video_pattern_2'], $url);
    }
}