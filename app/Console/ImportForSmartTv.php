<?php

namespace App\Console\Commands;

use App\Category;
use App\Library\Services\Cache\ChannelsCaching;
use App\Library\Services\Import\SmartTvImporter;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

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

        /** @var Builder $categories */
        $categories = Category::GetForTvApi();
        $this->info("Has " . $categories->count() . " categories for Smart TV.");

        $this->info("Getting channels.");
        $channels = $this->importer->getChannels();
        $this->info("Got " . count($channels) . " channels. Saving...");
        $this->importer->saveChannels($channels);

        if ($categories->count() and count($channels)) {
            $this->info("Getting broadcasts.");
            $broadcasts = $this->importer->getBroadcasts();
            $this->info("Got " . count($broadcasts) . " broadcasts. Saving...");
            $this->importer->saveBroadcasts($broadcasts, $categories->first()->id, $channels[0]['id_in_api']);
        } else {
            $this->error("No found category and channel for adding broadcasts.");
        }

        $this->info("Setting channels with broadcasts to cache.");
        ChannelsCaching::warmup();

        $this->info("Done.");
    }
}
