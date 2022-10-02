<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEquipmentExerciseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment_exercise', function (Blueprint $table) {
            $table->foreign(['equipment_id'])->references(['id'])->on('equipments')->onDelete('CASCADE');
            $table->foreign(['exercise_id'])->references(['id'])->on('exercises')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_exercise', function (Blueprint $table) {
            $table->dropForeign('equipment_exercise_equipment_id_foreign');
            $table->dropForeign('equipment_exercise_exercise_id_foreign');
        });
    }
}
