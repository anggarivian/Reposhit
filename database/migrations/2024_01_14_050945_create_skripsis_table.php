<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkripsisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skripsis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('dospem');
            $table->string('rilis');
            $table->string('halaman');
            $table->longText('abstrak');
            $table->string('file_skripsi');
            $table->boolean('status')->nullable();
            $table->integer('views')->default(0);
            $table->unsignedBigInteger('user_id')->nullable(); // Add user_id to link to users table
            $table->timestamps();
    
            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skripsis');
    }
}
