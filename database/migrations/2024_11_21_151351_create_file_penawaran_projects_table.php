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
        Schema::create('file_penawaran_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penawaran_project_id');
            $table->foreign('penawaran_project_id')->references('id')->on('penawaran_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_penawaran_projects');
    }
};
