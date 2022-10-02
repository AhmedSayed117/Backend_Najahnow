<?php

use App\Models\Member;
use App\Models\PrivateSession;
use Illuminate\Database\Seeder;

class PrivateSessionMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $privateSessions = PrivateSession::get();
        $members = Member::get();


        $members->each(function ($member) use ($privateSessions) {
            $filteredPrivateSessions = $privateSessions->filter(function ($session) {
                return $session->members->isEmpty();
            });
            $privateSession = $filteredPrivateSessions->random();
            $member->privateSessions()->save($privateSession, ['status' => 'pending', 'datetime' => '2021-09-13 14:13:51']);
        });
    }
}
