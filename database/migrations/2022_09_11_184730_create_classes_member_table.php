<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('classes_id');
            $table->unsignedBigInteger('member_id')->index('classes_member_member_id_foreign');
            $table->boolean('favourite');
            $table->timestamps();

            $table->unique(['classes_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes_member');
    }
}
