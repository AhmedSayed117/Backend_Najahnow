<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPlanMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan_member', function (Blueprint $table) {
            $table->foreign(['member_id'])->references(['id'])->on('members')->onDelete('CASCADE');
            $table->foreign(['plan_id'])->references(['id'])->on('plan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_member', function (Blueprint $table) {
            $table->dropForeign('plan_member_member_id_foreign');
            $table->dropForeign('plan_member_plan_id_foreign');
        });
    }
}
