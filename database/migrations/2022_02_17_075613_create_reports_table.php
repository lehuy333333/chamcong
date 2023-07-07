<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('total_timesheet')->nullable();
            $table->float('total_base_workdate')->nullable();
            $table->float('total_surplus_workdate')->nullable();
            $table->float('total_surplus_previous')->nullable();
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
