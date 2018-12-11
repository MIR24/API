<?php

use Faker\Generator as Faker;

$factory->define(\App\Channel::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'logo'=>$faker->name,
        'stream_shift'=>$faker->url,
        'stream_live'=>$faker->url
    ];
});
