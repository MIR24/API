<?php

namespace App\Library\Services\Commands;


use App\Country;
use App\Library\Services\ResultOfCommand;

class GetListOfCountries implements CommandInterface
{
    public function handle(array $options): ResultOfCommand
    {
        $countries = Country::GetForApi()->get();

        // TODO Кеширование?
        // ArrayList<Country> countries = (ArrayList) getServletContext().getAttribute("countries");
        // if (countries == null) {
        //     countries = getter.getCountries();
        //     getServletContext().setAttribute("countries", countries);
        // }

        return (new ResultOfCommand())
            ->setOperation('countries')
            ->setContent($countries)
            ->setMessage(sprintf("Total of %d countries.", count($countries)))
            ->setStatus(200);
    }
}
