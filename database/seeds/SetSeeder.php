<?php

use App\Models\Set;
use Illuminate\Database\Seeder;

class SetSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    factory(Set::class, 20)->create();
  }
}
