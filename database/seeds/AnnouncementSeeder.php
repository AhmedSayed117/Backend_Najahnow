<?php

use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
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
            factory(App\Models\Announcement::class,2)->create(['sender_id' => $i]);
        }

    }
}
