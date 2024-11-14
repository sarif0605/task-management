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
        Schema::create('penawaran_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade')->onUpdate('cascade');
            $table->string('pembuat_penawaran');
            $table->string('file_pdf');
            $table->string('pdf_public_id');
            $table->string('file_excel');
            $table->string('excel_public_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran_projects');
    }
};
