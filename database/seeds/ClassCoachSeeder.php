<?php

use Illuminate\Database\Seeder;

class ClassCoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


    $classes = App\Models\Classes::all();
        $coaches = App\Models\Coach::all();

        $coaches->each(function ($coach) use ($classes) {
            $coach->classes()->save($classes->random());
        });
    }
}
