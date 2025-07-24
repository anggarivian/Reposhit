<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();

            // Foreign key ke skripsis.id
            $table->unsignedBigInteger('skripsi_id');
            $table->foreign('skripsi_id')
                  ->references('id')->on('skripsis')
                  ->onDelete('cascade');

            // Metadata fields
            $table->string('title')->nullable()->comment('Judul skripsi (opsional duplikat)');
            $table->string('creator')->nullable()->comment('Nama penulis');
            $table->string('contributor')->nullable()->comment('Dosen pembimbing atau lainnya');
            $table->string('subject')->nullable()->comment('Topik atau bidang keilmuan');
            $table->text('description')->nullable()->comment('Abstrak / ringkasan isi');
            $table->string('publisher')->nullable()->comment('Nama institusi penerbit');
            $table->year('date_issued')->nullable()->comment('Tahun terbit / rilis');
            $table->string('language', 10)->nullable()->comment('ISO 639-1 (id, en, dsb.)');
            $table->string('type', 50)->nullable()->comment('Tipe dokumen: Skripsi, Tesis, Laporan, dll.');
            $table->string('format', 50)->nullable()->comment('Format file: PDF, DOCX, dll.');
            $table->string('identifier')->nullable()->comment('Link atau kode unik (misal DOI lokal)');
            $table->string('source')->nullable()->comment('Asal dokumen (jika terjemahan, revisi, dsb.)');
            $table->string('rights')->nullable()->comment('Status hak cipta (Open Access, dll.)');
            $table->string('keywords')->nullable()->comment('Kata kunci, dipisah koma');
            $table->string('coverage')->nullable()->comment('Cakupan: misal “Informatika - Pemrograman Web”');

            // Timestamps
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
        Schema::dropIfExists('metadata');
    }
}
