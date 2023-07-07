<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkSymbolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_symbols', function (Blueprint $table) {
            $table->id();
            $table->string('symbol_name');
            $table->string('symbol_id')->unique();
            $table->string('description')->nullable();
        });

        // DB::table('work_symbols')->insert([
        //     [
        //         'symbol_id' => 'X',
        //         'symbol_name' => 'Đi làm ',
        //         'description' => '1 công',
        //     ],
        //     [
        //         'symbol_id' => '1/2',
        //         'symbol_name' => 'Làm 1/2 ngày',
        //         'description' => '1/2 công',
        //     ],
        //     [
        //         'symbol_id' => '-l',
        //         'symbol_name' => 'Không bấm vào',
        //         'description' => '1 công',
        //     ],
        //     [
        //         'symbol_id' => 'l',
        //         'symbol_name' => 'Không bấm ra',
        //         'description' => '1 công',
        //     ],
        //     [
        //         'symbol_id' => 'Tr',
        //         'symbol_name' => 'Đi trễ',
        //         'description' => '1 công',
        //     ],
        //     [
        //         'symbol_id' => 'Sm',
        //         'symbol_name' => 'Về sớm',
        //         'description' => '1 công',
        //     ],
        //     [
        //         'symbol_id' => 'TL',
        //         'symbol_name' => 'Trực lễ',
        //         'description' => '',
        //     ],
        //     [
        //         'symbol_id' => 'CT',
        //         'symbol_name' => 'Công tác',
        //         'description' => '',
        //     ],
        //     [
        //         'symbol_id' => 'P',
        //         'symbol_name' => 'Nghỉ phép',
        //         'description' => '',
        //     ],
        //     [
        //         'symbol_id' => 'Om',
        //         'symbol_name' => 'Nghỉ ốm',
        //         'description' => '',
        //     ],
        //     [
        //         'symbol_id' => '-',
        //         'symbol_name' => 'Nghỉ',
        //         'description' => '',
        //     ],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_symbols');
    }
}
