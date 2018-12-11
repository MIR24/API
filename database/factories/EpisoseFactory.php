<?php

use Faker\Generator as Faker;

$factory->define(\App\Episode::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'poster'=>$faker->url,
        'season'=>$faker->numberBetween(1,5),
        'year'=>$faker->dateTimeThisYear()->format('Y'),
        'time_begin'=>$faker->dateTimeBetween(),
        'time_end'=>$faker->dateTimeBetween(),
        'url'=>$faker->url,
        'archive_id'=>$faker->numberBetween(1,5)
    ];
});
