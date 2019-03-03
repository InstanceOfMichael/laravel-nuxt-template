<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'title' => $faker->words($faker->numberBetween(5, 11), $asText = true).'?',
        'text' => $faker->paragraphs($faker->numberBetween(1, 3), $asText = true),
    ];
});
