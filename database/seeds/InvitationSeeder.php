<?php

use Illuminate\Database\Seeder;

class InvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <=10; $i++)
        {
            factory(App\Models\Invitation::class,2)->create(['user_id' => $i]);
        }
    }
}
