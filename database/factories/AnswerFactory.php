<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Answer;
use Faker\Generator as Faker;

$factory->define(Answer::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'question_id' => factory(App\Models\Question::class),
        'body' => $faker->sentence(),
    ];
});
