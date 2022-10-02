<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_meal', function (Blueprint $table) {
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('item_id')->index('item_meal_item_id_foreign');
            $table->unsignedBigInteger('meal_id')->index('item_meal_meal_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_meal');
    }
}
