<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToItemPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_plan', function (Blueprint $table) {
            $table->foreign(['item_id'])->references(['id'])->on('item')->onDelete('CASCADE');
            $table->foreign(['plan_id'])->references(['id'])->on('plan')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_plan', function (Blueprint $table) {
            $table->dropForeign('item_plan_item_id_foreign');
            $table->dropForeign('item_plan_plan_id_foreign');
        });
    }
}
