<?php

namespace Tests;


use App\Library\Services\Import\Mir24CountryImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsImportTest extends TestCase
{
    use RefreshDatabase;

    public function testCountryImporter()
    {
        $countriesNane = ['БЕЛАРУСЬ', 'РОССИЯ', 'ЕВРОПА', 'АЗИЯ'];
        /** @var  $importer Mir24CountryImporter */
        $importer = \App::make(Mir24CountryImporter::class);

        $countries = $importer->getCountries();
        $this->assertTrue(count($countries) > 0);
        foreach ($countriesNane as $countryName) {
            $this->assertDatabaseMissing('country', [
                'name' => $countryName,
            ]);
        }

        $importer->saveCountries($countries);
        foreach ($countriesNane as $countryName) {
            $this->assertDatabaseHas('country', [
                'name' => $countryName,
            ]);
        }
    }
}
