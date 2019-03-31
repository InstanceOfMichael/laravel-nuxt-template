<?php

use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'text' => $faker->paragraphs($faker->numberBetween(1, 3), $asText = true),
    ];
});
