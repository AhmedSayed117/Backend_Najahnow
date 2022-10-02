<?php

use App\Models\Meal;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meals = Meal::get();
        $plans = Plan::get();

        $meals->each(function ($meal) use ($plans) {
            $meal->plans()->save($plans->random(), ['type' => 'dinner', 'day' => 'monday']);
        });
    }
}
