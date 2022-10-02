<?php

use Illuminate\Database\Seeder;

class BranchSupplementarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supplementaries = App\Models\Supplementary::all();
        $branches = App\Models\Branch::all();

        $supplementaries->each(function ($supplementary) use ($branches) {
            $supplementary->branches()->save($branches->random(),['quantity' => 5]);
        });
    }
}
