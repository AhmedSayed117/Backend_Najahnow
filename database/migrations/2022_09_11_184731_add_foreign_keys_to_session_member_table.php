<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSessionMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_member', function (Blueprint $table) {
            $table->foreign(['member_id'])->references(['id'])->on('members')->onDelete('CASCADE');
            $table->foreign(['session_id'])->references(['id'])->on('private_sessions')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_member', function (Blueprint $table) {
            $table->dropForeign('session_member_member_id_foreign');
            $table->dropForeign('session_member_session_id_foreign');
        });
    }
}
