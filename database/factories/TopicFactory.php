<?php

use Faker\Generator as Faker;

$factory->define(App\Topic::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'text' => $faker->paragraphs($faker->numberBetween(1, 3), $asText = true),
    ];
});
