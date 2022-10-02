<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchSupplementaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_supplementary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplementary_id');
            $table->unsignedBigInteger('branch_id')->index('branch_supplementary_branch_id_foreign');
            $table->integer('quantity');
            $table->timestamps();

            $table->unique(['supplementary_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_supplementary');
    }
}
