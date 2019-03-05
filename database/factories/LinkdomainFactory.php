<?php

use Faker\Generator as Faker;

$factory->define(App\Linkdomain::class, function (Faker $faker) {
    return [
        'name' => $faker->words($faker->numberBetween(2, 4), $asText = true),
        'domain' => $faker->domainName,
    ];
});
