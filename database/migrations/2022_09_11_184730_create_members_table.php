<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_checked');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->string('medical_physical_history');
            $table->string('medical_allergic_history');
            $table->integer('available_frozen_days');
            $table->integer('available_membership_days');
            $table->integer('active_days');
            $table->integer('coach_id')->nullable();
            $table->integer('rate_count')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('membership_id')->index('members_membership_id_foreign');
            $table->unsignedBigInteger('current_plan')->nullable()->index('members_current_plan_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
