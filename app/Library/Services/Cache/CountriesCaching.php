<?php


namespace App\Library\Services\Cache;


use App\Country;
use Illuminate\Support\Facades\Cache;


class CountriesCaching
{
    public static function warmup(): void
    {
        $countries = Country::GetForApi()->get();
        Cache::forever("countries", $countries);
    }

    public static function get()
    {
        if (Cache::has("countries")) {
            $countries = Cache::get("countries");
        } else {
            $countries = Country::GetForApi()->get();
            Cache::forever("countries", $countries);
        }

        return $countries;
    }
}
