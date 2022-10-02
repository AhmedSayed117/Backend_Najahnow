<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSetGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('set_group', function (Blueprint $table) {
            $table->foreign(['group_id'])->references(['id'])->on('groups')->onDelete('CASCADE');
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
        Schema::table('set_group', function (Blueprint $table) {
            $table->dropForeign('set_group_group_id_foreign');
            $table->dropForeign('set_group_set_id_foreign');
        });
    }
}
