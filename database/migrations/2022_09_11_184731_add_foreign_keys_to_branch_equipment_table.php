<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBranchEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_equipment', function (Blueprint $table) {
            $table->foreign(['branch_id'])->references(['id'])->on('branches')->onDelete('CASCADE');
            $table->foreign(['equipment_id'])->references(['id'])->on('equipments')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_equipment', function (Blueprint $table) {
            $table->dropForeign('branch_equipment_branch_id_foreign');
            $table->dropForeign('branch_equipment_equipment_id_foreign');
        });
    }
}
