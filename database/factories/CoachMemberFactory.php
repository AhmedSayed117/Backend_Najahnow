<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CoachMember;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use App\Models\Member;
use App\Models\User;
use App\Models\Membership;


$factory->define(CoachMember::class, function (Faker $faker) {
    return [
        'start_date' => $faker->date,
        'end_date'=> $faker->date
    ];
});
