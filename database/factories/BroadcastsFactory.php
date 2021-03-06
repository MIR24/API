<?php

use Faker\Generator as Faker;

$factory->define(App\Broadcasts::class, function (Faker $faker) {
    return [
        'title'=>$faker->title,
        'subtitle'=>$faker->title,
        'age_restriction'=>$faker->numberBetween(0,18),
        'day_of_week'=>$faker->dayOfWeek,
        'time_begin'=>$faker->dateTimeBetween(),
        'time_end'=>$faker->dateTimeBetween(),
        'category_id'=>$faker->numberBetween(1,5),
        'channel_id'=>$faker->numberBetween(1,5)
    ];
});
