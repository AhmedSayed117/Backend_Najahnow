<?php

use Illuminate\Database\Seeder;

class CoachMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Models\CoachMember::class,5)->create(['member_id' => 30 ,'coach_id' => 60]);
//        $members = App\Models\Member::all();
//        $coaches = App\Models\Coach::all();
//        $size = $members->count();
//        $coaches->each(function ($coach) use ($members) {
//            $coach->members()->save($members->random(),['start_date' => '1970-04-03 00:00:00' , 'end_date' => '1987-09-26 00:00:00']);
//        });
        /*for($i = 1; $i <= $size; $i++)
        {
            $coaches[$i]->members()->attach((rand(1,$size)), ['start_date' => '1970-04-03 00:00:00' , 'end_date' => '1987-09-26 00:00:00']);
        }
        /*
         * $coaches->each(function ($coaches) use ($members)
        {

             $coaches->members()->attach($members->random(rand(1,3)) ->pluck('id')->toArray());
        });*/

    }
}
