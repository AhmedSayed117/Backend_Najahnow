<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExerciseSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exercise_set', function (Blueprint $table) {
            $table->foreign(['exercise_id'])->references(['id'])->on('exercises')->onDelete('CASCADE');
            $table->foreign(['set_id'])->references(['id'])->on('sets')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercise_set', function (Blueprint $table) {
            $table->dropForeign('exercise_set_exercise_id_foreign');
            $table->dropForeign('exercise_set_set_id_foreign');
        });
    }
}
