<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        for($i=1; $i <=20; $i++)
        {
            if(DB::table('users')->where('id', $i)->value('role') == 'member')
            {
                factory(App\Models\Member::class)->create(['user_id' => $i, 'membership_id' => (($i%3)+1)]);
            }



        }
    }
}
