<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('cal')->default(0);
            $table->string('title')->unique();
            $table->string('description');
            $table->string('image')->nullable()->default('https://media.istockphoto.com/photos/red-apple-with-leaf-isolated-on-white-background-picture-id185262648');
            $table->enum('level', ['green', 'yellow', 'red'])->default('green');
            $table->unsignedBigInteger('nutritionist_id')->index('item_nutritionist_id_foreign');
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
        Schema::dropIfExists('item');
    }
}
