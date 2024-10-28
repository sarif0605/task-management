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
        Schema::create('kasbon_opnams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('opnam_id');
            $table->foreign('opnam_id')->references('id')->on('opnams')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon_opnams');
    }
};
