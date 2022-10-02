<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'title' => $faker->sentence(1),
        'body' => $faker->sentence(),
    ];
});
