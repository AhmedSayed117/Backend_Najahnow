<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Equipment;
use Faker\Generator as Faker;

$factory->define(Equipment::class, function (Faker $faker) {
    return [
        'name'=>$faker->sentence(),
        'description'=>$faker->sentence(),
        'picture'=>$faker->imageUrl(640, 480),
    ];
});
