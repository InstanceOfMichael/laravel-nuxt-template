<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'title' => $faker->randomElement([
            'Does',
            'Does',
            'Do',
            'Do',
            'Are',
            'Are',
            'Aren\'t',
            'Can\'t',
            'Doesn\'t',
            'Don\'t',
            'Who',
            'Who',
            'Who',
            'What',
            'What',
            'What',
            'Where',
            'Where',
            'Where',
            'When',
            'When',
            'When',
            'How',
            'How',
            'How',
            'Which',
            'Which',
            'Which',
            'Whom',
            'Whose',
            'Why',
            'Why',
            'Why',
            'Whether',
            'Whatsoever',
        ]).' '.$faker->words($faker->numberBetween(4, 10), $asText = true).'?',
        'text' => $faker->paragraphs($faker->numberBetween(1, 3), $asText = true),
        'sides_type' => 0,
    ];
});
