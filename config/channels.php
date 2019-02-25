<?php


return [
    'mir24_tags' => [
        8, // признак потока в бд мир24
    ],

    'categories' => [
        15363867
    ],

    'streams' => [
        [
            'name' => 'МИР ПРЕМИУМ',
            'id_in_mir24' => 15363866,
            'id_in_api' => 1,
            'stream_shift' => 'http://hls.mirtv.cdnvideo.ru/mirtv-parampublish/hd/playlist.m3u8',
            'stream_live' => '',
            'logo' => 'http://onair.mir24.tv/images/custom/logo2.png',
        ]
    ],

    'tv_program_end_period' => 10080, // одна неделя в минутах (начинаем с программы недельной давности )

    'archive' => [
        'start_date' => '2018-01-01 00:00:00', // формат для mysql
        'video_pattern_1' => 'http://mirtv.ru/files/video/%s/%s', // для sprintf()
        'video_pattern_2' => 'http://mir24.tv/video/content/%s',
        'image_pattern_broadcast' => 'http://mirtv.ru/files/broadcast/%s/%s',
        'image_pattern_episode' => 'http://mirtv.ru/files/video/%s/%s',

    ]
];

