<?php

use Illuminate\Database\Seeder;

class ClassMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = App\Models\Member::all();
        $classes = App\Models\Classes::all();

        $classes->each(function ($class) use ($members) {
            $class->members()->save($members->random(),['favourite' => true]);
        });
    }
}
