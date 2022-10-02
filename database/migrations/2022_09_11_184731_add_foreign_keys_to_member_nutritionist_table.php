<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMemberNutritionistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_nutritionist', function (Blueprint $table) {
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
        Schema::table('member_nutritionist', function (Blueprint $table) {
            $table->dropForeign('member_nutritionist_member_id_foreign');
            $table->dropForeign('member_nutritionist_nutritionist_id_foreign');
        });
    }
}
