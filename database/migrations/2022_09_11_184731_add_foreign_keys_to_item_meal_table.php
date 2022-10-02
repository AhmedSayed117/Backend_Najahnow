<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToItemMealTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_meal', function (Blueprint $table) {
            $table->foreign(['item_id'])->references(['id'])->on('item')->onDelete('CASCADE');
            $table->foreign(['meal_id'])->references(['id'])->on('meal')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_meal', function (Blueprint $table) {
            $table->dropForeign('item_meal_item_id_foreign');
            $table->dropForeign('item_meal_meal_id_foreign');
        });
    }
}
