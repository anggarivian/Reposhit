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
            $table->string('npm');
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('email_verified_at')->nullable();
            $table->string('tgl_lahir');
            $table->string('alamat');
            $table->string('angkatan');
            $table->string('prodi');
            $table->string('password');
            $table->foreignId('roles_id')->constrained();
            $table->rememberToken();
            $table->timestamps();
        });
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
