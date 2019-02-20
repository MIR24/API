<?php

namespace App\Library\Services\Import;


use App\Broadcasts;
use App\Channel;
use Illuminate\Support\Facades\DB;

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

    public function saveBroadcasts($broadcasts, $category_id = null, $channel_id = null): self
    {
        $category_id = $category_id ?? $this->params['categories'][0];
        $channel_id = $channel_id ?? $this->params['streams'][0]['id_in_api'];

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

}
