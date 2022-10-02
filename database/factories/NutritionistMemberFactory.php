<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\NutritionistMember;
use Faker\Generator as Faker;

$factory->define(NutritionistMember::class, function (Faker $faker) {
    return [
        'start_date' => $faker->date,
        'end_date'=> $faker->date
    ];
});
