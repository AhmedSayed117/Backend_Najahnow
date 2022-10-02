<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('coach_id');
            $table->unsignedBigInteger('member_id')->index('coach_member_member_id_foreign');
            $table->timestamp('start_date')->default('2022-09-11 18:41:24');
            $table->timestamp('end_date')->default('2022-09-11 18:41:24');
            $table->timestamps();

            $table->unique(['coach_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_member');
    }
}
