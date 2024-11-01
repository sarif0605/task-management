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
        Schema::create('constraints', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('deal_project_id');
            $table->foreign('deal_project_id')->references('id')->on('deal_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal');
            $table->text('pekerjaan');
            $table->text('progress');
            $table->text('kendala');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constraints');
    }
};
