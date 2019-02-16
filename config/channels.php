<?php


return [
    'mir24_tags'=>[
        8, // признак потока в бд мир24
    ],

    'streams' => [
        15363866 => [ //МИР ПРЕМИУМ он же МИР HD
            'stream' => 'http://hls.mirtv.cdnvideo.ru/mirtv-parampublish/hd/playlist.m3u8',
            'live' => '',
            'logo' => 'http://onair.mir24.tv/images/custom/logo.png',
        ]
    ],

    'tv_program_end_period'=>10080, // одна неделя в минутах (начинаем с программы недельной давности )



];

