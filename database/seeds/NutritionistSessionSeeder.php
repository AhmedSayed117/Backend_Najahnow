<?php

use Illuminate\Database\Seeder;

class NutritionistSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = App\Models\Member::all();
        $nutritionists = App\Models\Nutritionist::all();

        $nutritionists->each(function ($nutritionist) use ($members) {
            $nutritionist->nutritionistSessions()->save($members->random(), ['date' => '1987-09-26 00:00:00']);
        });
    }
}
