<?php

use Illuminate\Database\Seeder;

class FeedbackComplaintsSeeder extends Seeder
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
            factory(App\Models\FeedbackComplaints::class,2)->create(['user_id' => $i]);
        }

    }
}
