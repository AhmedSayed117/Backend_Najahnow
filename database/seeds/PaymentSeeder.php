<?php

use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = App\Models\Member::all();

        for($i=1; $i <=$members->count(); $i++)
        {
            factory(App\Models\Payment::class,2)->create(['member_id' => $i]);
        }

    }
}
