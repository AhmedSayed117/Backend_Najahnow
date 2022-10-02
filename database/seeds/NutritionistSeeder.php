<?php

use Illuminate\Database\Seeder;

class NutritionistSeeder extends Seeder
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
            if(DB::table('users')->where('id', $i)->value('role') == 'nutritionist')
            {
                factory(App\Models\Nutritionist::class)->create(['user_id' => $i]);
            }



        }
    }
}
