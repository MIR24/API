<?php

namespace App\Console\Commands;

use App\Library\Services\Import\Mir24CategoryImporter;
use App\Library\Services\Import\Mir24CountryImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InitialImportFromMir24 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:mir24:initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import categories and countries from mir24';


    private $categoryImporter;
    private $countryImporter;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Mir24CategoryImporter $categoryImporter, Mir24CountryImporter $countryImporter)
    {
        parent::__construct();
        $this->categoryImporter = $categoryImporter;
        $this->countryImporter = $countryImporter;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Init UPDATE_COMPLETE parameter");
        DB::insert("INSERT IGNORE INTO `status` VALUES (1,'UPDATE_COMPLETE',1,NULL,NULL);");

        $this->info("Starting update.");

        $this->info("Getting categories.");
        $categories = $this->categoryImporter->getCategories();
        $this->info("Got " . count($categories) . " categories. Saving...");
        $this->categoryImporter->updateCategories($categories);

        $this->info("Getting countries.");
        $countries = $this->countryImporter->getCountries();
        $this->info("Got " . count($countries) . " countries. Saving...");
        $this->countryImporter->saveCountries($countries);

        $this->info("Saving types of comments...");
        DB::insert("INSERT IGNORE INTO `types` VALUES (0,'news'),(1,'photo'),(2,'video');");

        $this->info("Done.");
    }
}
