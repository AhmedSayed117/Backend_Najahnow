<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNutritionistSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nutritionist_sessions', function (Blueprint $table) {
            $table->foreign(['member_id'])->references(['id'])->on('members')->onDelete('CASCADE');
            $table->foreign(['nutritionist_id'])->references(['id'])->on('nutritionists')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nutritionist_sessions', function (Blueprint $table) {
            $table->dropForeign('nutritionist_sessions_member_id_foreign');
            $table->dropForeign('nutritionist_sessions_nutritionist_id_foreign');
        });
    }
}
