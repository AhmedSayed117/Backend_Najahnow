<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\NutritionistSession;
use Faker\Generator as Faker;

$factory->define(NutritionistSession::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTime,
    ];
});
