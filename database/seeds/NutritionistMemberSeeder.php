<?php

use Illuminate\Database\Seeder;

class NutritionistMemberSeeder extends Seeder
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
            $nutritionist->members()->save($members->random(),['start_date' => '1970-04-03 00:00:00' , 'end_date' => '1987-09-26 00:00:00']);
        });

    }
}
