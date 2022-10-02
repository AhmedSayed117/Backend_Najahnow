<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Branch;
use App\Models\User;
use App\Models\UserInfo;
use Faker\Generator as Faker;


$factory->define(UserInfo::class, function (Faker $faker) {
    return [
        'user_id'=>factory(User::class),
//        'gender'=>'male',
        'gender'=>$faker->randomElement(['male','female']),
        'photo'=>$faker->filePath(),
//        'photo'=>'images/1633002070.png',
        'bio'=>$faker->sentence(),
//        'bio'=>'iam admin for this gym',
        'branch_id'=>factory(Branch::class),
        'weight'=>$faker->randomDigitNotZero(),
        'height'=>$faker->randomDigitNotZero(),
        'calories'=>$faker->randomDigitNotZero(),
        'age'=>$faker->randomDigitNotZero(),
        'activity_level'=>$faker->randomElement([1,2,3,4]),
        "Protein"=>$faker->randomDigitNotZero(),
        "Carbs"=>$faker->randomDigitNotZero(),
        "Fats"=>$faker->randomDigitNotZero(),
    ];
});
