<?php

namespace Tests;


use App\Library\Services\Import\Mir24CategoryImporter;
use App\Library\Services\Import\Mir24CountryImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsImportTest extends TestCase
{
    use RefreshDatabase;

    public function testCountryImporter()
    {
        $countriesName = ['БЕЛАРУСЬ', 'РОССИЯ', 'ЕВРОПА', 'АЗИЯ'];
        /** @var  $importer Mir24CountryImporter */
        $importer = \App::make(Mir24CountryImporter::class);

        $countries = $importer->getCountries();
        $this->assertTrue(count($countries) > 0);
        foreach ($countriesName as $countryName) {
            $this->assertDatabaseMissing('country', [
                'name' => $countryName,
            ]);
        }

        $importer->saveCountries($countries);
        foreach ($countriesName as $countryName) {
            $this->assertDatabaseHas('country', [
                'name' => $countryName,
            ]);
        }
    }

    public function testCategoryImporter()
    {
        $categoriesName = ["ЭКОНОМИКА", "СТИЛЬ ЖИЗНИ", "СПОРТ", "В МИРЕ"];
        /** @var  $importer Mir24CategoryImporter */
        $importer = \App::make(Mir24CategoryImporter::class);

        $categories = $importer->getCategories();
        $this->assertTrue(count($categories) > 0);
        foreach ($categoriesName as $categoryName) {
            $this->assertDatabaseMissing('categories', [
                'name' => $categoryName,
            ]);
        }

        $importer->updateCategories($categories);
        foreach ($categoriesName as $categoryName) {
            $this->assertDatabaseHas('categories', [
                'name' => $categoryName,
            ]);
        }
    }
}
