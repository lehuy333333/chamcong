<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('type_repair')->nullable();
            $table->string('result')->nullable();
            $table->unsignedBigInteger('department_id');

            $table->foreign('department_id')->references('id')->on('departments');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->float('total_overtime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
