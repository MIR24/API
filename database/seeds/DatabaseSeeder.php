<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(
            [
                CategoryTableSeader::class,
                ChannelTableSeeder::class,
                BroadcastsTableSeeder::class,
                ArchiveTableSeeder::class,
                EpisodeTableSeeder::class,
            ]
        );
    }
}
