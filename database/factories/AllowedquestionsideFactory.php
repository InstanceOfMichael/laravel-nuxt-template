<?php

use Faker\Generator as Faker;

$factory->define(App\Allowedquestionside::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->colorName,
    ];
});
