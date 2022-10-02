<?php

use App\Models\PrivateSession;
use Illuminate\Database\Seeder;

class PrivateSessionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(PrivateSession::class, 20)->create();
  }
}
