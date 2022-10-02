<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitnessSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fitness_summary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('BMI', 8, 2);
            $table->double('weight', 8, 2);
            $table->double('muscle_ratio', 8, 2);
            $table->double('height', 8, 2);
            $table->double('fat_ratio', 8, 2);
            $table->double('fitness_ratio', 8, 2);
            $table->double('total_body_water', 8, 2);
            $table->double('dry_lean_bath', 8, 2);
            $table->double('body_fat_mass', 8, 2);
            $table->double('opacity_ratio', 8, 2);
            $table->double('protein', 8, 2);
            $table->double('SMM', 8, 2);
            $table->timestamps();
            $table->unsignedBigInteger('member_id')->index('fitness_summary_member_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fitness_summary');
    }
}
