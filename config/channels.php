<?php


return [
    'mir24_tags' => [
        8, // признак потока в бд мир24
    ],

    'categories' => [
        15363867
    ],

    'categories_tv' => [
        ['id' => 1, 'name' => 'Кухни Мира'],
        ['id' => 2, 'name' => 'Мир.DOC'],
        ['id' => 3, 'name' => 'Непознанное или Загадки и тайны'],
        ['id' => 4, 'name' => 'Новости и аналитика'],
        ['id' => 5, 'name' => 'Сериалы'],
        ['id' => 6, 'name' => 'Телешоу'],
    ],

    // cat ./docs/Передачи\ МИР\ и\ МИР24\ -\ МИР\ Премиум.csv |perl -l -n -e "if( \$_ =~ m/^([^,]+),.*,([^,]+?)\s*$/) { if(\$2) { print qq/['name' =\> '\$1', 'category' => '\$2'],/}}"
    'archives' => [
        ['name' => 'Документальные фильмы', 'category' => 'Мир.DOC'],
        ['name' => 'Культ//Туризм', 'category' => 'Новости и аналитика'],
        ['name' => 'Мой лучший друг', 'category' => 'Телешоу'],
        ['name' => 'Такие странные', 'category' => 'Телешоу'],
        ['name' => 'Ой, мамочки!', 'category' => 'Телешоу'],
        ['name' => 'Еще дешевле', 'category' => 'Телешоу'],
        ['name' => 'Специальный репортаж', 'category' => 'Мир.DOC'],
        ['name' => 'Наше кино. История большой любви', 'category' => 'Телешоу'],
        ['name' => 'Казахстан. Легенды степи', 'category' => 'Непознанное или Загадки и тайны'],
        ['name' => 'Пять причин поехать в ...', 'category' => 'Телешоу'],
        ['name' => 'Дела семейные. Новые истории', 'category' => 'Телешоу'],
        ['name' => 'Достучаться до звезды', 'category' => 'Телешоу'],
        ['name' => 'Игра в кино', 'category' => 'Телешоу'],
        ['name' => 'Такие разные', 'category' => 'Телешоу'],
        ['name' => 'В гостях у цифры', 'category' => 'Новости и аналитика'],
        ['name' => 'Евразия. Спорт', 'category' => 'Новости и аналитика'],
        ['name' => 'Тайны времени', 'category' => 'Непознанное или Загадки и тайны'],
        ['name' => 'Беларусь сегодня', 'category' => 'Новости и аналитика'],
        ['name' => 'Секретные материалы', 'category' => 'Непознанное или Загадки и тайны'],
        ['name' => 'Бремя обеда', 'category' => 'Кухни Мира'],
        ['name' => 'Миллион вопросов о природе', 'category' => 'Телешоу'],
        ['name' => 'С миру по нитке', 'category' => 'Телешоу'],
        ['name' => 'Наши иностранцы', 'category' => 'Телешоу'],
        ['name' => 'Держись, шоубиз!', 'category' => 'Новости и аналитика'],
        ['name' => 'Вместе', 'category' => 'Новости и аналитика'],
        ['name' => 'Школа выживания от одинокой женщины с тремя детьми в условиях кризиса', 'category' => 'Сериалы'],
        ['name' => 'Развод', 'category' => 'Сериалы'],
        ['name' => 'Новости', 'category' => 'Новости и аналитика'],
    ],

    'streams' => [
        [
            'name' => 'МИР ПРЕМИУМ',
            'id_in_mir24' => 15363866,
            'id_in_api' => 1,
            'stream_shift' => '',
            'stream_live' => 'http://hls.mirtv.cdnvideo.ru/mirtv-parampublish/hd/playlist.m3u8',
            'logo' => 'http://onair.mir24.tv/images/custom/logo.png',
        ]
    ],

    'tv_program_end_period' => 10080, // одна неделя в минутах (начинаем с программы недельной давности )

    'archive' => [
        'start_date' => '2018-01-01 00:00:00', // формат для mysql
        'video_pattern_1' => 'http://mirtv.ru/files/video/%s/%s', // для sprintf()
        'video_pattern_2' => 'http://mir24.tv/video/content/%s',
        'image_pattern_broadcast' => 'http://mirtv.ru/files/broadcast/%s/%s',
        'image_pattern_broadcast_default' => 'http://mirtv.ru/files/broadcast/%s/main.jpg',
        'image_pattern_episode' => 'http://mirtv.ru/files/video/%s/%s',

    ]
];

