<?php

use Illuminate\Database\Seeder;
use App\Models\Classes;
class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Classes::class, 5)->create();

    }
}
