<?php

use Faker\Generator as Faker;

$factory->define(App\Claimrelation::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement([
            App\Claimrelation::REBUTE,
            App\Claimrelation::COLLABORATE,
            App\Claimrelation::PREMISE,
        ]),
    ];
});
