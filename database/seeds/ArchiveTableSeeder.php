<?php

use Illuminate\Database\Seeder;

class ArchiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Archive::class, 5)->make()->each(function ($arhive){
            $arhive->save();
        });
    }
}
