<?php

namespace App\Console\Commands;

use App\Library\Services\Import\Mir24CategoryImporter;
use App\Library\Services\Import\Mir24CountryImporter;
use Illuminate\Console\Command;

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
    protected $description = 'Import catagories and countries from mir24';


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
        // TODO INSERT INTO `types` VALUES (0,'news'),(1,'photo'),(2,'video');
        // TODO INSERT INTO `status` VALUES (1,'UPDATE_COMPLETE',1,NULL,NULL);
        # TODO $this->importer->setUpdateComplete(false);

        $this->info("Starting update.");

        $this->info("Getting categories.");
        $categories = $this->categoryImporter->getCategories();
        $this->info("Got " . count($categories) . " categories. Saving...");
        $this->categoryImporter->updateCategories($categories);

        # TODO Category: show:=true

        $this->info("Getting countries.");
        $countries = $this->countryImporter->getCountries();
        $this->info("Got " . count($countries) . " countries. Saving...");
        $this->countryImporter->saveCountries($countries);

        $this->info("Done.");

        # TODO $this->importer->setUpdateComplete(true);
    }
}
