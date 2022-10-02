<?php

use Illuminate\Database\Seeder;

class BranchEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = App\Models\Branch::all();
        $equipments = App\Models\Equipment::all();

        $equipments->each(function ($equipment) use ($branches) {
            $equipment->branches()->save($branches->random(),['quantity' => 5]);
        });
    }
}
