<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coach;
use App\Models\Exercise;
use Faker\Generator as Faker;

$factory->define(Exercise::class, function (Faker $faker) {
  return [

    'description' => $faker->paragraph(),
    'duration' => $faker->time('H:i', 'now'),
    'gif' => $faker->url(),
    'cal_burnt' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 50),
    'title' => $faker->word(),
    'reps' => $faker->randomDigit,
    'image' => $faker->imageUrl(640, 480),
    'coach_id' => Coach::all()->random()->id,
  ];
});
