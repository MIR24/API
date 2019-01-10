<?php

namespace App\Library\Services\Commands;


use App\Library\Services\Cache\CountriesCaching;
use App\Library\Services\ResultOfCommand;


class GetListOfCountries implements CommandInterface
{
    public function handle(array $options): ResultOfCommand
    {
        $countries = CountriesCaching::get();

        return (new ResultOfCommand())
            ->setOperation('countries')
            ->setContent($countries)
            ->setMessage(sprintf("Total of %d countries.", count($countries)))
            ->setStatus(200);
    }
}
