<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.2.19
 * Time: 16.56
 */

namespace App\Console\Commands;

use App\Episode;
use App\Library\Services\Cache\ArchivesCaching;
use App\Library\Services\Cache\ChannelsCaching;
use App\Library\Services\Import\SmartTvVideo;
use Illuminate\Console\Command;

class VideoInfoSmartTv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set duration information about video';

    /**
     * @var SmartTvVideo
     */
    private $video;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SmartTvVideo $video)
    {
        parent::__construct();
        $this->video = $video;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = 0;

        $this->info('Start set duration for video');
        foreach (Episode::all() as $item) {
            if ($item->time_begin == $item->time_end) {
                $this->line("Fixing duration in episode {$item->id}");
                $this->video->fixVideoEpisode($item);
                $count++;
            }

        }
        $this->info("Changed {$count} videos");

        $this->info("Setting channels with broadcasts to cache.");
        ChannelsCaching::warmup();
        $this->info("Setting archives to cache.");
        ArchivesCaching::warmup();
    }
}
