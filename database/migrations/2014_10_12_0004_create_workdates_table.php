<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workdates', function (Blueprint $table) {
            $table->id();
            $table->date('workdate')->unique();
            $table->float('work_coefficient')->default(1); //hệ số ngày công
            $table->string('holiday')->nullable(); // ngày lễ, ngày nghỉ
            $table->boolean('isHoliday')->default(0);
            $table->boolean('isLock')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workdates');
    }
}
