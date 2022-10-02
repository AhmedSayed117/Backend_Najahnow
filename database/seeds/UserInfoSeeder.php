<?php


use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class UserInfoSeeder extends Seeder
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
            factory(UserInfo::class)->create(['user_id' => $i, 'branch_id' => (($i%2)+1)]);
        }
    }
}
