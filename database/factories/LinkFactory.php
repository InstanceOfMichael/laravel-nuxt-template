<?php

use Faker\Generator as Faker;

$factory->define(App\Link::class, function (Faker $faker) {
    return [
        'title' => $faker->words($faker->numberBetween(3, 9), $asText = true),
        'url' => $faker->url.implode('/', $faker->words($faker->numberBetween(1, 3))),
    ];
});
