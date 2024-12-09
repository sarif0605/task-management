<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('deal_project_id');
            $table->foreign('deal_project_id')->references('id')->on('deal_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->string('pekerjaan');
            $table->enum('status', ['plan', 'mulai', 'selesai', 'belum'])->default('plan')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->float('bobot')->nullable();
            $table->float('progress')->nullable();
            $table->float('durasi', 5)->nullable();
            $table->float('harian')->nullable();
            $table->integer('excel_row_number')->nullable();
            $table->uuid('updated_by')->nullable(); // Track who updated the record
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_projects');
    }
};
