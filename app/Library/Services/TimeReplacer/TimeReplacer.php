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

    private function replace(Collection $data): Collection
    {
        $data->map(function ($item) {
            $item['time'] = [
                'begin' => $item['time_begin'], # TODO достаточно только время без даты
                'end' => $item['time_end'],
            ];
            unset($item['time_begin']);
            unset($item['time_end']);

            return $item;
        });

        return $data;
    }
}
