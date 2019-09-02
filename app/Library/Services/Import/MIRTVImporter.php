<?php


namespace App\Library\Services\Import;


use Illuminate\Support\Facades\DB;

class MIRTVImporter
{
    const MIRTV = 'mirtv';
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
        $this->params['days'] = date(DATE_W3C, strtotime($this->params['days']));
    }


    public function getPremiere()
    {
        $query = "select  premiere_id, title, description, start  from premiere  where active = 1 and mainpage_top = 1 and published_start < now() and published_stop > now() order by start  limit ?";
        return DB::connection(self::MIRTV)->select($query,[$this->params['limit']]);
    }
}
