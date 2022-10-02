<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'member_id'=> factory(\App\Models\Member::class),
        'price'=> 100,
        'type'=> $faker->word,
    ];
});
