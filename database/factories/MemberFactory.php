<?php

/** @var Factory $factory */

use App\Models\Member;
use App\Models\User;
use App\Models\Membership;
use App\Models\Plan;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Member::class, function (Faker $faker) {
    return [
        'is_checked' => $faker->boolean,
        'start_date' => $faker->date,
        'end_date'=> $faker->date,
        'medical_physical_history'=> $faker->sentence,
        'medical_allergic_history'=> $faker->sentence,
        'available_frozen_days'=> $faker->randomDigit(),
        'available_membership_days'=> $faker->randomDigit(),
        'active_days'=> $faker->randomDigit(),
        'membership_id'=>factory(App\Models\Membership::class),
        'user_id'=>factory(App\Models\User::class),
        'current_plan'=> Plan::all()->random()->id
    ];
});
