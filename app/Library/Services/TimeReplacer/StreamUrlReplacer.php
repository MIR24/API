<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.12.18
 * Time: 10.34
 */

namespace App\Library\Services\TimeReplacer;


use Illuminate\Support\Collection;

# TODO Remove it and use App\Http\Resources\ChannelResource
class StreamUrlReplacer
{
    public function replace(Collection $data, $keyFirst = "stream_shift", $keySecond = "stream_live"): Collection
    {
        $data->map(function ($item) use ($keyFirst, $keySecond) {
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
