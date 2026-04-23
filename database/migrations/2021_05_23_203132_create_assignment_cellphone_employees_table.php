<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentCellphoneEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_cellphone_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cellphone_id')->constrained('cellphones');
            $table->foreignId('employee_id')->constrained('employees');
            $table->foreignId('number_id')->nullable()->constrained('numbers');
            //stastus 0:inactivo, 1:activo
            $table->tinyInteger('status');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('assignment_cellphone_employees');
    }
}
