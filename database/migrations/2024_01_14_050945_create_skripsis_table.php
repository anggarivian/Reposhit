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
            $table->longtext('abstrak');
            $table->string('dospem');
            $table->string('rilis');
            $table->string('halaman');
            $table->string('cover');
            $table->string('bab1');
            $table->string('bab2');
            $table->string('bab3');
            $table->string('bab4');
            $table->string('bab5');
            $table->string('dapus');
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
        Schema::dropIfExists('skripsis');
    }
}
