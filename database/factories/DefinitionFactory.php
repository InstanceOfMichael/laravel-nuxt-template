<?php

use Faker\Generator as Faker;

$factory->define(App\Definition::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->colorName,
        'text' => $faker->paragraphs($faker->numberBetween(1, 3), $asText = true),
    ];
});
