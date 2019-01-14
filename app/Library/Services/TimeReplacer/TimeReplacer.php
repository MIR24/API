<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.12.18
 * Time: 10.34
 */

namespace App\Library\Services\TimeReplacer;


use Illuminate\Support\Collection;


class TimeReplacer
{
    public function replaceForArchive(Collection $data): Collection
    {
        $data = $this->replace($data);
        $data->each(function ($item) {
            $item['episodes'] = $this->replace($item['episodes']);
        });

        return $data;
    }

    public function replaceForChannel(Collection $data): Collection
    {
        $data->each(function ($item) {
            $item['broadcasts'] = $this->replace($item['broadcasts']);
        });

        return $data;
    }

    private function replace(Collection $data, $keyBegin = "time_begin", $keyEnd = "time_end"): Collection
    {
        $data->map(function ($item) use ($keyBegin, $keyEnd) {
            $item['time'] = [
                'begin' => $item[$keyBegin],
                'end' => $item[$keyEnd],
            ];
            unset($item[$keyBegin]);
            unset($item[$keyEnd]);

            return $item;
        });

        return $data;
    }
}
