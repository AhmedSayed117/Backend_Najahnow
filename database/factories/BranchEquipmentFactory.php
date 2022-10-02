<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BranchEquipment;
use Faker\Generator as Faker;

$factory->define(BranchEquipment::class, function (Faker $faker) {
    return [
        'branch_id'=>factory(\App\Models\BranchEquipment::class),
        'equipment_id'=>factory(\App\Models\BranchEquipment::class),
        'quantity'=>$faker->randomDigit()
    ];
});
