<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description');
            $table->string('duration');
            $table->string('gif');
            $table->double('cal_burnt', 8, 2);
            $table->string('title');
            $table->integer('reps');
            $table->string('image');
            $table->string('music')->nullable();
            $table->unsignedBigInteger('coach_id')->index('exercises_coach_id_foreign');
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
        Schema::dropIfExists('exercises');
    }
}
