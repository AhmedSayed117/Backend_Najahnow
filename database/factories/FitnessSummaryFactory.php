<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fitness_Summary;
use App\Models\Member;
use Faker\Generator as Faker;

$factory->define(Fitness_Summary::class, function (Faker $faker) {
    return [
        //
        'BMI' => $this->faker->randomFloat(3, 0, 100),
        'weight' => $this->faker->randomFloat(3, 20, 200),
        'muscle_ratio' => $this->faker->randomFloat(3, 0, 100),
        'height' => $this->faker->randomFloat(3, 50, 250),
        'fat_ratio' => $this->faker->randomFloat(3, 0, 100),
        'fitness_ratio' => $this->faker->randomFloat(3, 0, 100),
        'total_body_water' => $this->faker->randomFloat(3, 0, 200),
        'dry_lean_bath' => $this->faker->randomFloat(3, 0, 200),
        'body_fat_mass' => $this->faker->randomFloat(3, 0, 200),
        'opacity_ratio' => $this->faker->randomFloat(3, 0, 100),
        'protein' => $this->faker->randomFloat(3, 0, 200),
        'SMM' => $this->faker->randomFloat(3, 0, 100),
        'member_id' => Member::all()->random()->id,
    ];
});
