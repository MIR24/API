<?php

namespace App\Library\Services\Import;


use App\Archive;
use App\Broadcasts;
use App\CategoryTv;
use App\Channel;
use App\Episode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartTvImporter
{
    private $params;

    const MIR24 = 'mir24';

    const MIRHD = 'mirhd';

    /**
     * SmartTvImporter constructor.
     * @param $params array
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getCategories(): array
    {
        return $this->params['categories_tv'];
    }


    public function saveCategories($categories): self
    {
        foreach ($categories as $category) {
            CategoryTv::updateOrCreate(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                ]
            );
        }

        return $this;
    }

    public function getChannels(): array
    {
        return $this->params['streams'];
    }


    public function saveChannels($channels): self
    {
        foreach ($channels as $channel) {
            Channel::updateOrCreate(
                ['id' => $channel['id_in_api']],
                [
                    'name' => $channel['name'],
                    'stream_shift' => $channel['stream_shift'],
                    'stream_live' => $channel['stream_live'],
                    'logo' => $channel['logo'],
                ]
            );
        }

        return $this;
    }

    public function getBroadcasts(): array
    {

        $query = "SELECT teleprogramm_id, title, description, min_age, start FROM teleprogramm_mirhd " .
            "where `start` > ?  order by `start` asc";

        $start_time = (new \DateTime("now - {$this->params['tv_program_end_period']} minute"))->format(DATE_W3C);

        return DB::connection(self::MIRHD)->select($query, [$start_time]);
    }

    public function saveBroadcasts($broadcasts, $category_id, $channel_id): self
    {
        $end = null;
        $buff = null;

        $this->addTimeEnd($broadcasts);

        foreach ($broadcasts as $broadcast) {
            /**
             * @var $res Broadcasts
             */
            $atributes = [
                'title' => $broadcast->title,
                'subtitle' => $broadcast->description,
                'age_restriction' => $broadcast->min_age,
                'day_of_week' => (new \DateTime($broadcast->start))->format('l'),
                'time_begin' => $broadcast->start,
                'time_end' => isset($broadcast->end) ? $broadcast->end : $broadcast->start,
                'category_id' => $category_id,
                'channel_id' => $channel_id,
            ];

            $res = Broadcasts::find($broadcast->teleprogramm_id);

            if ($res) {
                $res->update($atributes);
            } else {
                $res = new Broadcasts(array_merge(['id' => $broadcast->teleprogramm_id], $atributes));
            }

            $res->save();
        }
        return $this;
    }

    private function addTimeEnd(&$broadcasts)
    {
        $past = null;

        foreach ($broadcasts as $broadcast) {

            if ($past == null) {
                $past = $broadcast;
            } else {
                $past->end = (new \DateTime($broadcast->start))->format("Y-m-d H:i:s");
                $past = $broadcast;
            }

        }

        reset($broadcasts);
        //We can not single out the end of the last item in the program,
        //because we do not know when to start the next
        //TODO find duration or end tim last elemetn into tv program
        array_pop($broadcasts);
    }

    public function getArchive()
    {
        $query = "SELECT ar.article_id id, ar.title ,ar.description  , ar.image , " .
            "ep.episode_id   ep_id, ep.episode ep_name ,ep.title  ep_title, ep.image  ep_img, ep.`start`  ep_str " .
            "FROM episode ep " .
            "join article ar on ar.article_id = ep.article_id " .
            "where ep.`start` > ? " .
            "order by ar.article_id";

        return DB::connection(self::MIRHD)->select($query, [$this->params['archive']['start_date']]);
    }

    public function saveArchive($archives, $category_id = 1)
    {
        foreach ($archives as $archive) {

            $year = (new \DateTime($archive->ep_str))->format('Y');

            $attribute_archive = [
                'title' => $archive->title,
                'category_id' => $category_id,
                'poster' => $this->getUrlImageArchive($archive->id, $archive->image),
            ];

            $attribute_episode = [
                'title' => $this->getTitle($archive->ep_title),
                'poster' => $this->getUrlImageEpisode($archive->ep_id, $archive->ep_img),
                'season' => $this->getSeason($year, $archive->id),
                'year' => $year,
                'time_begin' => $archive->ep_str,
                'url' => $this->getUrlVideo($archive->ep_id, $archive->ep_name),
                'archive_id' => $archive->id,
            ];

            /**
             * @var $ar Archive
             */
            if ($ar = Archive::find($archive->id)) {
                $ar->update($attribute_archive);
            } else {
                $ar = new Archive(array_merge(['id' => $archive->id], $attribute_archive));
            }
            $ar->save();

            /**
             * @var $ep Episode
             */
            if ($ep = Episode::find($archive->ep_id)) {
                $ep->update($attribute_episode);
            } else {
                $ep = new Episode(array_merge(['id' => $archive->ep_id], $attribute_episode,
                    ['time_end' => $archive->ep_str]));
            }
            $ep->save();
        }
    }

    private function getSeason($year, $id, $border_date = '2019-01-01 00:00:00')
    {
        $query = "SELECT true FROM  episode WHERE article_id = ? AND `start` < ? limit 1";

        switch ($year) {
            case '2018':
                return 1;
            case '2019':
                return DB::connection(self::MIRHD)->select($query, [$id, $border_date]) ? 2 : 1;
            default:
                Log::error('Not found year into episodes');
                return 1;
        }
    }

    //TODO may be need more actual parser...
    private function getUrlVideo($id, $url)
    {
        if ($url == 'video.mp4') {
            return sprintf($this->params['archive']['video_pattern_1'], $id, $url);
        } else {
            return sprintf($this->params['archive']['video_pattern_2'], $url);
        }
    }

    private function getUrlImageArchive($id, $url)
    {
        if (!empty($url)) {
            return sprintf($this->params['archive']['image_pattern_broadcast'], $id, $url);
        } else {
            return sprintf($this->params['archive']['image_pattern_broadcast_default'], $id);
        }
    }

    private function getUrlImageEpisode($id, $url)
    {
        return sprintf($this->params['archive']['image_pattern_episode'], $id, $url);
    }

    private function getTitle($title)
    {
        return str_replace("\n", "", strip_tags($title));
    }
}
