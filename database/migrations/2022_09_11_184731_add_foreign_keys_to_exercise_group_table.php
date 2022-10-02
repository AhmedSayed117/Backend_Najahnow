<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToExerciseGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exercise_group', function (Blueprint $table) {
            $table->foreign(['exercise_id'])->references(['id'])->on('exercises')->onDelete('CASCADE');
            $table->foreign(['group_id'])->references(['id'])->on('groups')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercise_group', function (Blueprint $table) {
            $table->dropForeign('exercise_group_exercise_id_foreign');
            $table->dropForeign('exercise_group_group_id_foreign');
        });
    }
}
