<?php

use App\Models\Exercise;
use App\Models\Set;
use Illuminate\Database\Seeder;

class ExerciseSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = Exercise::get();

        $exercises->each(function ($exercise) {
            $sets = $exercise->coach->sets;
            $exercise->sets()->save($sets->random(), ['break_duration' => '01:00']);
        });
    }
}
