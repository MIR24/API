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

    const MIRTV = 'mirtv';

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

    public function getBroadcasts($connection=self::MIRHD): array
    {
        if ($connection == self::MIRHD) {
            $query = "SELECT t.teleprogramm_id, t.title, t.description, t.min_age, t.start, a.title as article_title"
                . " FROM teleprogramm_mirhd t LEFT JOIN article a ON t.article_broadcast_id = a.article_id"
                . " where t.start > ?  order by t.start asc";
        }
        if ($connection == self::MIR24) {
            $query = "SELECT t.id teleprogramm_id, t.brand_name title, t.desc description, t.age min_age, t.date start, t.time"
                . " FROM programms t"
                . " where t.date > ?  order by t.date asc";
        }
        if ($connection == self::MIRTV) {
            $query = "SELECT t.teleprogramm_id, t.title, t.description, t.min_age, t.start"
                . " FROM teleprogramm_mir t"
                . " where t.start > ?  order by t.start asc";
        }

        $start_time = (new \DateTime("now - {$this->params['tv_program_end_period']} minute"))->format(DATE_W3C);
        $connection == self::MIRHD ? self::MIRTV : $connection;
        return DB::connection($connection)->select($query, [$start_time]);
    }

    public function saveBroadcasts($broadcasts, $channel_id, $isCategory = true): self
    {
        $end = null;
        $buff = null;
        $categories = $this->getCategoryNames();

        $this->addTimeEnd($broadcasts);

        foreach ($broadcasts as $broadcast) {

            if ($isCategory) {
                $category_id = array_key_exists($broadcast->article_title, $categories)
                    ? $categories[$broadcast->article_title] ?? null
                    : array_key_exists($broadcast->title, $categories)
                        ? $categories[$broadcast->title] ?? null
                        : null;
            }

            $atributes = [
                'title' => $broadcast->title,
                'subtitle' => $broadcast->description,
                'age_restriction' => empty($broadcast->min_age) ? 0 : $broadcast->min_age,
                'day_of_week' => (new \DateTime($broadcast->start))->format('l'),
                'time_begin' => isset($broadcast->time) ? $broadcast->start . ' ' . $broadcast->time : $broadcast->start,
                'time_end' => isset($broadcast->end) ? $broadcast->end : $broadcast->start,
                'category_id' => $isCategory ? $category_id : null,
                'channel_id' => $channel_id,
            ];

            /**
             * @var $res Broadcasts
             */
            $res = Broadcasts::where('old_id', $broadcast->teleprogramm_id)
                ->where('channel_id',$channel_id)
                ->first();

            if ($res) {
                $res->update($atributes);
            } else {
                $res = new Broadcasts(array_merge(['old_id' => $broadcast->teleprogramm_id], $atributes));
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

    public function saveArchive($archives)
    {
        $categories = $this->getCategoryNames();

        foreach ($archives as $archive) {
            $year = (new \DateTime($archive->ep_str))->format('Y');

            $attribute_archive = [
                'title' => $archive->title,
                'category_id' => array_key_exists($archive->title, $categories) ? $categories[$archive->title] : null,
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

    private function getCategoryNames()
    {
        $categories = [];
        foreach ($this->params['archives'] as $itemArch) {
            $filteredCategoriesTv = array_values(array_filter($this->params['categories_tv'],
                function ($itemCat) use ($itemArch) {
                    return $itemCat['name'] === $itemArch['category'];
                }));
            if (count($filteredCategoriesTv)) {
                $categories[$itemArch['name']] = $filteredCategoriesTv[0]['id'];
            }
        }

        return $categories;
    }
}
