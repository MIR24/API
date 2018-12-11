<?php

use Faker\Generator as Faker;

$factory->define(App\Archive::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'category_id'=>$faker->numberBetween(1,5),
        'poster'=>$faker->url,
        'url'=>$faker->url,
        'time_begin'=>$faker->dateTimeBetween(),
        'time_end'=>$faker->dateTimeBetween(),
    ];
});
