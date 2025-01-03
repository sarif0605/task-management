<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_produk', 100)->nullable();
            $table->date('tanggal');
            $table->string('pemilik', 100)->nullable();
            $table->text('lokasi')->nullable();
            $table->string('no_telp', 14)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['prospek', 'survey', 'penawaran', 'deal'])->default('prospek')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
