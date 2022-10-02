<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Announcement;
use Faker\Generator as Faker;

$factory->define(Announcement::class, function (Faker $faker) {
    return
    [
        'title'=> $faker->sentence,
        'description'=> $faker->paragraph,
        'datetime'=> $faker->datetime,
        'sender_id'=> factory(\App\Models\User::class),
        'receiver_type'=> $faker->word,
    ];
});
