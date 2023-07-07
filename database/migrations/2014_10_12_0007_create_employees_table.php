<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employeeID')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique()->nullable();;
            // $table->date('birthday')->nullable();
            // $table->boolean('sex')->nullable();
            // $table->string('ID_number')->nullable();
            // $table->date('ID_date')->nullable();
            // $table->string('ID_place')->nullable();
            // $table->string('address')->nullable();
            $table->float('personal_coefficient')->nullable(); // hệ số cá nhân
            // $table->float('employee_type'); // khối nhân viên
            $table->unsignedBigInteger('department_id'); // phòng ban
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('employee_type_id');
            $table->softDeletes();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('position_id')->references('id')->on('positions');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('employee_type_id')->references('id')->on('employee_types');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
