<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_summaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('calories_burnt', 8, 2);
            $table->date('date');
            $table->unsignedBigInteger('member_id')->index('workout_summaries_member_id_foreign');
            $table->string('duration');
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
        Schema::dropIfExists('workout_summaries');
    }
}
