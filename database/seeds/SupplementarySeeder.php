<?php

use Illuminate\Database\Seeder;

class SupplementarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Supplementary::class,2)->create();
    }
}
