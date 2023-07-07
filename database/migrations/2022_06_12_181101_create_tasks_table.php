<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->date('added_on')->nullable();
            $table->string('name');
            $table->string('device_name');
            $table->longText('description')->nullable();
            $table->longText('remedies')->nullable(); //biện pháp khắc phục
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('interruption_time')->nullable(); //thời gian gián đoạn
            $table->string('interruption_cause')->nullable(); //nguyên nhân gián đoạn
            $table->string('type_repair')->nullable(); //loại sửa chữa
            $table->string('result')->nullable(); //kết quả
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
