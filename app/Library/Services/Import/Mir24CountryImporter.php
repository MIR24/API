<?php

namespace App\Library\Services\Import;

use Illuminate\Support\Facades\DB;

class Mir24CountryImporter
{
    public function getCountries(): array
    {
        $query = "SELECT  id, (deleted_at IS NULL) AS published, UPPER(title) as name "
            . "FROM    tags "
            . "WHERE   type = 2 ";

        return DB::connection('mir24')->select($query);
    }

    public function saveCountries(array $countries): void
    {
        $query = "INSERT INTO country (`id`,`name`,`published`) "
            . "VALUES (?, ?, ?) "
            . "ON DUPLICATE KEY UPDATE name=?, published=?";

        foreach ($countries as $country) {
            DB::insert(
                $query,
                [$country->id, $country->name, $country->published, $country->name, $country->published]
            );
        }
    }
}
