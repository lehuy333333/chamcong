<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('fullname')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('level_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('department_id')->references('id')->on('departments');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('level_id')->references('id')->on('levels');
        });

        // DB::table('users')->insert(
        //     array(
        //         'username' => 'admin',
        //         'email' => 'admin@support.com',
        //         'password' => Hash::make('123456'),
        //         'level_id' => 1,
        //     )
        // );

        // DB::table('users')->insert(
        //     array(
        //         'username' => 'KTVT',
        //         'email' => 'ktvt@support.com',
        //         'password' => Hash::make('123456'),
        //         'level_id' => 3,
        //         'department_id' => 1,
        //     )
        // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
