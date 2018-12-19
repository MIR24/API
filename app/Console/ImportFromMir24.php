<?php

namespace App\Console\Commands;

use App\Library\Services\Import\Mir24Importer;
use Illuminate\Console\Command;

class ImportFromMir24 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:mir24';

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
        $output = $this->importer->run();
        $this->info($output);
    }
}
