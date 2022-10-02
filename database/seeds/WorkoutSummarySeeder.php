<?php

use Illuminate\Database\Seeder;
use App\WorkoutSummary;
class WorkoutSummarySeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        for($i=1; $i <=20; $i++)
        {
            if(DB::table('users')->where('id', $i)->value('role') == 'member')
            {
                factory(App\Models\WorkoutSummary::class)->create(['member_id' => App\Models\Member::all()->random()->id]);
            }
        }

    }
}
