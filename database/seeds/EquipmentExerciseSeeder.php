<?php

use App\Models\Exercise;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = Exercise::get();
        $equipments = Equipment::get();

        $exercises->each(function ($exercise) use ($equipments) {
            $exercise->equipments()->save($equipments->random());
        });
    }
}
