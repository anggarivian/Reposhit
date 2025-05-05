<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('skripsi_id'); // Change to unsignedBigInteger
            $table->unsignedBigInteger('id_user');    // Change to unsignedBigInteger
            $table->text('content');
            $table->unsignedBigInteger('parent_id')->nullable(); // Change to unsignedBigInteger
            $table->timestamps();
    
            // Add foreign key constraints
            $table->foreign('skripsi_id')->references('id')->on('skripsis')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
