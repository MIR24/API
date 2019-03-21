<?php

use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = config('channels.streams');
        foreach ($channels as $channelData) {
            $channelData['id'] = $channelData['id_in_api'];
            unset($channelData['id_in_api']);
            unset($channelData['id_in_mir24']);

            (new \App\Channel($channelData))->save();
        }
    }
}
