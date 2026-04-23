<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropNumberIdFromCellphonesTable extends Migration
{
    public function up()
    {
        if ($this->usesSqlite()) {
            return;
        }

        Schema::table('cellphones', function (Blueprint $table) {
            $table->dropForeign(['number_id']);
            $table->dropColumn('number_id');
        });
    }

    public function down()
    {
        if ($this->usesSqlite()) {
            return;
        }

        Schema::table('cellphones', function (Blueprint $table) {
            $table->foreignId('number_id')->nullable()->after('department_id')->constrained('numbers')->onDelete('cascade');
        });
    }

    protected function usesSqlite()
    {
        return DB::getDriverName() === 'sqlite';
    }
}
