<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WorkoutSummary;
use App\Models\Member;
use Faker\Generator as Faker;

$factory->define(WorkoutSummary::class, function (Faker $faker) {
    return [
        'calories_burnt' => $faker->randomFloat(4, 0, 2000),
        'date' => $faker->date(),
        'member_id' => factory(Member::class),    //this gets random id from the table Members into workout summary
        'duration' => $faker->randomFloat(2, 0, 3),
    ];
});
