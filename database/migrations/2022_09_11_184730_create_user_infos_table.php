<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('gender')->default('male');
            $table->integer('weight')->nullable();
            $table->integer('height')->nullable();
            $table->integer('calories')->nullable();
            $table->integer('age')->nullable();
            $table->integer('activity_level')->nullable();
            $table->string('photo');
            $table->string('bio')->nullable();
            $table->unsignedBigInteger('branch_id')->index('user_infos_branch_id_foreign');
            $table->timestamps();
            $table->double('Protein')->nullable();
            $table->double('Carbs')->nullable();
            $table->double('Fats')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
}
