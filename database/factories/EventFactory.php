<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return
    [
            'title' => $faker->sentence,
            'description' => $faker->paragraph,
            'start_time' => $faker->datetime,
            'end_time' => now(),
            'tickets_available' => 5,
            'price' => 100,
            'status' => $faker->sentence,
    ];
});
