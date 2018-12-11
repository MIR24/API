<?php

use Illuminate\Database\Seeder;

class EpisodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Episode::class, 10)->make()->each(function ($episode){
            $episode->save();
        });
    }
}
