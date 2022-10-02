<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Item;
use App\Models\Nutritionist;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        //
        'cal' => $this->faker->numberBetween($min = 100, $max = 10000),
        'title' => $this->faker->unique()->word(),
        'description' => $this->faker->text(),
        'image' => 'https://media.istockphoto.com/photos/red-apple-with-leaf-isolated-on-white-background-picture-id185262648',
        'level' => $this->faker->randomElement($array = array ('red','green','yellow')),
    'nutritionist_id' => Nutritionist::all()->random()->id,
    ];
});
