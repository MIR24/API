<?php

namespace App\Console\Commands;

use App\Category;
use App\Library\Services\Import\SmartTvImporter;
use Illuminate\Console\Command;

class ImportForSmartTv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:smarttv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for SmartTV';


    private $importer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SmartTvImporter $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Starting update.");

//        $categories = Category::GetForTvApi();
//        $this->info("Has " . count($categories) . " categories for Smart TV.");

        $this->info("Getting channels.");
        $channels = $this->importer->getChannels();
        $this->info("Got " . count($channels) . " channels. Saving...");
        $this->importer->saveChannels($channels);

        $this->info("Getting broadcasts.");
        $broadcasts = $this->importer->getBroadcasts();
        $this->info("Got " . count($broadcasts) . " broadcasts. Saving...");
        $this->importer->saveBroadcasts($broadcasts,15363867,1);

        $this->info("Done.");
    }
}
