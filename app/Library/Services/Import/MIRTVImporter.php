<?php


namespace App\Library\Services\Import;


use Illuminate\Support\Facades\DB;
use test\Mockery\ReturnTypeObjectTypeHint;

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
        $query = "SELECT premiere_id, title, description, start FROM premiere where start > ? order by start desc limit ?";

        return DB::connection(self::MIRTV)->select($query, [$this->params['days'], $this->params['limit']]);
    }
}
