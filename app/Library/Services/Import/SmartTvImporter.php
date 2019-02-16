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
        # TODO статический массив возвращать и всё. Или вообще из БД убрать, только в конфиге хранить
        $query = "select id, title as name from tags where type = ?";

        return DB::connection(self::MIR24)->select($query, $this->params['mir24_tags']);
    }


    public function saveChannels($channels): self
    {
        foreach ($channels as $channel) {
            if (isset($this->params['streams'][$channel->id])) {
                Channel::updateOrCreate(
                    ['name' => $channel->name], // Unique field is name...Oh, a lot of trouble will bring us a name change
                    [
                        'stream_shift' => $this->params['streams'][$channel->id]['stream'],
                        'stream_live' => $this->params['streams'][$channel->id]['live'],
                        'logo' => $this->params['streams'][$channel->id]['logo'],
                    ]
                );
            }
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

            if ($res)
                $res->update($atributes);
            else
                $res=new Broadcasts(array_merge(['id' => $broadcast->teleprogramm_id],$atributes));

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
