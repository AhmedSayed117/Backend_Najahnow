<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Coach;
use Faker\Generator as Faker;

$factory->define(Coach::class, function (Faker $faker) {
    return [

        'is_checked'=> $faker->boolean(),
        'user_id'=>factory(App\Models\User::class)


    ];
});
