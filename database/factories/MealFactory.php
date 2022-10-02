<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Meal;
use App\Models\Nutritionist;
use Faker\Generator as Faker;

$factory->define(Meal::class, function (Faker $faker) {
    return [
        //
        'title' => $this->faker->unique()->word(),
        'description' => $this->faker->paragraph,
    'nutritionist_id' => Nutritionist::all()->random()->id,
    ];
});
