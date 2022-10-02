<?php

use Illuminate\Database\Seeder;

class EventUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = App\Models\Event::all();
        $users = App\Models\User::all();

        $events->each(function ($event) use ($users) {
            $event->users()->save($users->random(),['status' => 'done']);
        });
    }
}
