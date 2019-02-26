<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.12.18
 * Time: 10.34
 */

namespace App\Library\Services\TimeReplacer;


use Illuminate\Support\Collection;

class StreamUrlReplacer
{
    public function replace(Collection $data, $keyFirst = "stream_shift", $keySecond = "stream_live"): Collection
    {
        $data->map(function ($item) use ($keyFirst, $keySecond) {

            if(isset($item['week_broadcasts'])){
                $item['broadcasts']=$item['week_broadcasts'];
                unset($item['week_broadcasts']);
            }

            $item['stream'] = [
                'shift' => $item[$keyFirst],
                'live' => $item[$keySecond],
            ];
            unset($item[$keyFirst]);
            unset($item[$keySecond]);

            return $item;
        });

        return $data;
    }
}
