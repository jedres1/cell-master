<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCellphonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cellphones', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('brand');
            $table->string('imei');
            $table->string('accessories')->nullable();
            $table->tinyInteger('status');
            //foreign key department
            $table->foreignId('department_id')->constrained('departments');
            //foreign key department
            $table->foreignId('number_id')->nullable()->constrained('numbers')->onDelete('cascade');
            //foreign key company
            $table->foreignId('company_id')->constrained('companies');
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
        Schema::dropIfExists('cellphones');
    }
}
