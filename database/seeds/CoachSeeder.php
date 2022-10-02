<?php

use Illuminate\Database\Seeder;
use App\Models\Coach;
class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

         for($i=1; $i <=20; $i++)
        {
            if(DB::table('users')->where('id', $i)->value('role') == 'coach')
            {
                factory(App\Models\Coach::class)->create(['user_id' => $i]);
            }



        }
    }
}
