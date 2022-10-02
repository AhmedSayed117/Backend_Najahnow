<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('duration');
            $table->unsignedBigInteger('member_id')->index('plan_member_member_id_foreign');
            $table->unsignedBigInteger('plan_id')->index('plan_member_plan_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_member');
    }
}
