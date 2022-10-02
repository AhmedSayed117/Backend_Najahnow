<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Supplementary;
use Faker\Generator as Faker;

$factory->define(Supplementary::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(1),
        'description' => $faker->paragraph(),
        'price' => $faker->numberBetween(1,1000),
        'picture' => $faker -> sentence(1)
    ];
});
