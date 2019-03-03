<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'pc_id' => 0,
        'text' => $faker->paragraphs($faker->numberBetween(1, 3), $asText = true),
    ];
});
