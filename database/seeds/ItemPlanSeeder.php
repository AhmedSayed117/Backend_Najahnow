<?php

use App\Models\Item;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class ItemPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = Item::get();
        $plans = Plan::get();

        $items->each(function ($item) use ($plans) {
            $item->plans()->save($plans->random(), ['quantity' => 1, 'type' => 'snack', 'day' => 'monday']);
        });
    }
}
