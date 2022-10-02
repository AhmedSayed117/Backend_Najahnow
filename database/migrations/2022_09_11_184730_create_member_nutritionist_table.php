<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberNutritionistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_nutritionist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nutritionist_id');
            $table->unsignedBigInteger('member_id')->index('member_nutritionist_member_id_foreign');
            $table->timestamp('start_date')->default('2022-09-11 18:41:25');
            $table->timestamp('end_date')->default('2022-09-11 18:41:25');
            $table->timestamps();

            $table->unique(['nutritionist_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_nutritionist');
    }
}
