<?php

use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <=3; $i++)
        {
            factory(App\Models\Membership::class)->create( ['branch_id'=> (($i%2)+1)]);
        }

    }
}
