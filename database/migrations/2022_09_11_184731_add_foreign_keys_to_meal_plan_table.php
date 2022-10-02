<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMealPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meal_plan', function (Blueprint $table) {
            $table->foreign(['meal_id'])->references(['id'])->on('meal')->onDelete('CASCADE');
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
        Schema::table('meal_plan', function (Blueprint $table) {
            $table->dropForeign('meal_plan_meal_id_foreign');
            $table->dropForeign('meal_plan_plan_id_foreign');
        });
    }
}
