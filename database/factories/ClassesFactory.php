<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Classes;
use Faker\Generator as Faker;

$factory->define(Classes::class, function (Faker $faker) {
    return [
        'title'=>$faker->sentence(),
        'description'=>$faker->sentence(),
        'level'=>$faker->randomElement(['easy','medium','hard']),
        'link'=>$faker->url(),
        'capacity'=>$faker->randomFloat(0, 0, 100),
        'price'=>$faker->randomFloat(2, 50, 200),
        'duration'=>$faker->randomFloat(2, 0, 3),
        'date'=>$faker->date(),
    ];
});
