<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Invitation;
use Faker\Generator as Faker;

$factory->define(Invitation::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User::class),
        'name' => $faker->name,
        'number' => "012345667",
    ];
});
