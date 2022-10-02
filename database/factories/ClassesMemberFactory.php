<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ClassesMember;
use App\Models\Classes;
use App\Models\Member;
use Faker\Generator as Faker;

$factory->define(ClassesMember::class, function (Faker $faker) {

    return [
        'classes_id'=>factory(\App\Models\Classes::class),
        'member_id'=>factory(\App\Models\Member::class),
        'favourite'=>$faker->boolean()
    ];
});
