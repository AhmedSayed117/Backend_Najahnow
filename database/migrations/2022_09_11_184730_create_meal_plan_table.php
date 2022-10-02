<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->enum('day', ['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->unsignedBigInteger('plan_id')->index('meal_plan_plan_id_foreign');
            $table->unsignedBigInteger('meal_id')->index('meal_plan_meal_id_foreign');
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
        Schema::dropIfExists('meal_plan');
    }
}
