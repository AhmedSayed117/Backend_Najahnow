<?php

use App\Models\Group;
use App\Models\Member;
use Illuminate\Database\Seeder;

class GroupMemberSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $groups = Group::get();
    $members = Member::get();


    $members->each(function ($member) use ($groups) {
      $member->groups()->save($groups->random(), ['day' => '2021-08-23']);
    });
  }
}
