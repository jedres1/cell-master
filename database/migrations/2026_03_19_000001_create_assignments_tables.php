<?php

use App\Cellphone;
use App\Employee;
use App\Number;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTables extends Migration
{
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(1);
            $table->string('note')->nullable();
            $table->timestamps();
        });

        Schema::create('assignment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->string('assignable_type');
            $table->unsignedBigInteger('assignable_id');
            $table->string('slot')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['assignable_type', 'assignable_id']);
            $table->unique(['assignment_id', 'assignable_type']);
        });

        if (Schema::hasTable('assignment_cellphone_employees')) {
            $legacyAssignments = DB::table('assignment_cellphone_employees')->get();

            foreach ($legacyAssignments as $legacyAssignment) {
                DB::table('assignments')->insert([
                    'id' => $legacyAssignment->id,
                    'status' => $legacyAssignment->status,
                    'note' => $legacyAssignment->note,
                    'created_at' => $legacyAssignment->created_at,
                    'updated_at' => $legacyAssignment->updated_at,
                ]);

                if ($legacyAssignment->employee_id) {
                    DB::table('assignment_items')->insert([
                        'assignment_id' => $legacyAssignment->id,
                        'assignable_type' => Employee::class,
                        'assignable_id' => $legacyAssignment->employee_id,
                        'slot' => 'employee',
                        'sort_order' => 1,
                        'created_at' => $legacyAssignment->created_at,
                        'updated_at' => $legacyAssignment->updated_at,
                    ]);
                }

                if ($legacyAssignment->cellphone_id) {
                    DB::table('assignment_items')->insert([
                        'assignment_id' => $legacyAssignment->id,
                        'assignable_type' => Cellphone::class,
                        'assignable_id' => $legacyAssignment->cellphone_id,
                        'slot' => 'cellphone',
                        'sort_order' => 2,
                        'created_at' => $legacyAssignment->created_at,
                        'updated_at' => $legacyAssignment->updated_at,
                    ]);

                    $cellphoneNumberId = DB::table('cellphones')
                        ->where('id', $legacyAssignment->cellphone_id)
                        ->value('number_id');

                    if ($cellphoneNumberId) {
                        DB::table('assignment_items')->insert([
                            'assignment_id' => $legacyAssignment->id,
                            'assignable_type' => Number::class,
                            'assignable_id' => $cellphoneNumberId,
                            'slot' => 'number',
                            'sort_order' => 3,
                            'created_at' => $legacyAssignment->created_at,
                            'updated_at' => $legacyAssignment->updated_at,
                        ]);
                    }
                }
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('assignment_items');
        Schema::dropIfExists('assignments');
    }
}
