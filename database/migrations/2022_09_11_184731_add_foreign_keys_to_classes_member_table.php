<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClassesMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classes_member', function (Blueprint $table) {
            $table->foreign(['classes_id'])->references(['id'])->on('classes')->onDelete('CASCADE');
            $table->foreign(['member_id'])->references(['id'])->on('members')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classes_member', function (Blueprint $table) {
            $table->dropForeign('classes_member_classes_id_foreign');
            $table->dropForeign('classes_member_member_id_foreign');
        });
    }
}
