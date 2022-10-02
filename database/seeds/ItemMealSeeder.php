<?php

use App\Models\Item;
use App\Models\Meal;
use Illuminate\Database\Seeder;

class ItemMealSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $meals = Meal::get();
    $items = Item::get();

    $items->each(function ($item) use ($meals) {
      $item->meals()->save($meals->random(), ['quantity' => random_int(1, 20)]);
    });
  }
}
