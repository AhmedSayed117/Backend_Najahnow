<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\FeedbackComplaints;
use Faker\Generator as Faker;

$factory->define(FeedbackComplaints::class, function (Faker $faker) {
    return
    [
        'user_id'=> factory(\App\Models\User::class),
        'description'=> $faker->paragraph,
        'title'=> $faker->sentence,
        'type'=> $faker->randomElement(['feedback', 'complaint']),
    ];
});
