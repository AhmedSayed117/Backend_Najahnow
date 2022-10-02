<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Branch;
use Faker\Generator as Faker;

$factory->define(Branch::class, function (Faker $faker) {
    return [
        'location' => $faker->address(),
        'title' => $faker->word(),
        'phone_number' => $faker->phoneNumber(),
        'crowd_meter' => $faker->numberBetween(0,1000),
        'picture' => $faker->filePath(),
        'info' => $faker->paragraph(),
        'members_number' => $faker->numberBetween(10,100),
        'coaches_number' => $faker->numberBetween(0,20),
    ];
});
