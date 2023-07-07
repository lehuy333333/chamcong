<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWorkdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workdates', function (Blueprint $table) {
            $table->boolean('isWeekend')->default(0);
        });

        Schema::table('work_symbols', function (Blueprint $table) {
            $table->float('work_symbols_coefficient')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workdates', function (Blueprint $table) {
            //
        });
    }
}
