<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workdate_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('work_symbol_id');
            $table->float('overtime')->nullable();
            $table->float('overtime_night')->nullable();
            $table->float('work_coefficient')->nullable(); 
            $table->string('explain')->nullable();
            $table->boolean('duty');
        });
        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreign('workdate_id')->references('id')->on('workdates');
        });
        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees');
        });
        Schema::table('timesheets', function (Blueprint $table) {
            $table->foreign('work_symbol_id')->references('id')->on('work_symbols');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheets');
    }
}
