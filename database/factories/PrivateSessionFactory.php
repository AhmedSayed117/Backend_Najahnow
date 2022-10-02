<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coach;
use App\Models\PrivateSession;
use Faker\Generator as Faker;

$factory->define(PrivateSession::class, function (Faker $faker) {
  return [
        'title' => $faker->word(),
    'description' => $faker->paragraph(),
    'link' => $faker->url(),
    'duration' => $faker->time('H:i', 'now'),
    'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 50),
    'coach_id' => Coach::all()->random()->id, 


  ];
});
