<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPrivateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('private_sessions', function (Blueprint $table) {
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
        Schema::table('private_sessions', function (Blueprint $table) {
            $table->dropForeign('private_sessions_coach_id_foreign');
        });
    }
}
