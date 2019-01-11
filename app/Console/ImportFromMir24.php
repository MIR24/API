<?php

namespace App\Console\Commands;

use App\Library\Services\Cache\CountriesCaching;
use App\Library\Services\Cache\NewsCaching;
use App\Library\Services\Cache\NewsIdCaching;
use App\Library\Services\Import\Mir24Importer;
use Illuminate\Console\Command;

class ImportFromMir24 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:mir24 { --period= : Период обновления новостей в минутах }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import tables from mir24';


    private $importer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Mir24Importer $importer)
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
        $period = $this->option("period");

        # TODO Не запускать, если обновление уже запущено
        $this->importer->setUpdateComplete(false);

        $this->info("Starting update.");

        $this->info("Getting news.");
        $news = $this->importer->getLastNews($period);
        $this->info("Got " . count($news) . " news. Saving...");
        $this->importer->saveLastNews($news);

        $this->info("Getting actual news info.");
        $actualNews = $this->importer->getActualNewsId();
        $this->info("Got " . count($actualNews) . " hits. Saving...");
        $this->importer->updateActualNews($actualNews);

        $this->info("Getting tags.");
        $tags = $this->importer->getTags($period);
        $this->info("Got " . count($tags) . " tags. Saving...");
        $this->importer->saveTags($tags);

        $this->info("Getting news tags info.");
        $newsTags = $this->importer->getNewsTags($news);
        $this->info("Got " . count($newsTags) . " news tags info. Saving...");
        $this->importer->saveNewsTags($newsTags);

        $this->info("Getting photos for news with galleries.");
        $galleries = $this->importer->getGalleries($news);
        $this->info("Got " . count($galleries) . " galleries. Saving...");
        $this->importer->saveGalleries($galleries);

        $this->info("Getting country links.");
        $links = $this->importer->getNewsCountryLinks($news);
        $this->info("Got " . count($links) . " links. Saving...");
        $this->importer->saveNewsCountryLinks($links);

        $this->info("Setting last news, last news with gallery and last news with videos to cache.");
        NewsCaching::warmup();

        $this->info("Setting countries to cache.");
        CountriesCaching::warmup();

        $this->info("Setting search table to cache.");
        NewsIdCaching::warmup();

        $this->info("Done.");

        $this->importer->setUpdateComplete(true);
    }
}
