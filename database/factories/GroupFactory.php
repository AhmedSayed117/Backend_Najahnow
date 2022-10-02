<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coach;
use App\Models\Group;
use Faker\Generator as Faker;

$factory->define(Group::class, function (Faker $faker) {
  return [
    'description' => $faker->paragraph(),
    'coach_id' => Coach::all()->random()->id,
        'title' => $faker->word(),

  ];
});
