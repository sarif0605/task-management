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
        Schema::create('materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('report_project_id');
            $table->foreign('report_project_id')->references('id')->on('report_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal')->nullable();
            $table->text('pekerjaan');
            $table->text('material');
            $table->text('priority');
            $table->date('for_date');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
