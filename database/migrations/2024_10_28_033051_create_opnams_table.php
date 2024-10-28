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
        Schema::create('opnams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('operational_project_id');
            $table->foreign('operational_project_id')->references('id')->on('operational_projects');
            $table->date('date');
            $table->text('pekerjaan');
            $table->text('opnams');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opnams');
    }
};
