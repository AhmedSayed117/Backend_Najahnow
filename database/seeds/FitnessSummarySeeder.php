<?php

use Illuminate\Database\Seeder;

class FitnessSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Models\Fitness_Summary::class, 10)->create();
    }
}
