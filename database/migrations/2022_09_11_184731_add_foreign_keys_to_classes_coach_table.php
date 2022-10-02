<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClassesCoachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes_coach', function (Blueprint $table) {
            $table->foreign(['classes_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['coach_id'])->references(['id'])->on('coaches')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes_coach', function (Blueprint $table) {
            $table->dropForeign('classes_coach_classes_id_foreign');
            $table->dropForeign('classes_coach_coach_id_foreign');
        });
    }
}
