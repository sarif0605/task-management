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
            $table->uuid('report_project_id');
            $table->foreign('report_project_id')->references('id')->on('report_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->text('pekerjaan');
            $table->string('opnam');
            $table->float('kasbon_1');
            $table->float('kasbon_2');
            $table->float('kasbon_3');
            $table->text('keterangan');
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
