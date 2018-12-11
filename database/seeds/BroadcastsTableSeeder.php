<?php

use Illuminate\Database\Seeder;

class BroadcastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Broadcasts::class, 10)->make()->each(function ($broudcasts){
            $broudcasts->save();
        });
    }
}
