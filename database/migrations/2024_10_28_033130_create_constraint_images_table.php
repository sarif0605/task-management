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
        Schema::create('constraint_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('constraint_id');
            $table->foreign('constraint_id')->references('id')->on('constraints')->onDelete('cascade')->onUpdate('cascade');
            $table->string('image_link');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constraint_images');
    }
};
