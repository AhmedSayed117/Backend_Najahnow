<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('set_id')->index('set_group_set_id_foreign');
            $table->unsignedBigInteger('group_id')->index('set_group_group_id_foreign');
            $table->string('break_duration');
            $table->integer('order');
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
        Schema::dropIfExists('set_group');
    }
}
