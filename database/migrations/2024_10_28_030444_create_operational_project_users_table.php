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
        Schema::create('operational_project_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('operational_project_id');
            $table->foreign('operational_project_id')->references('id')->on('operational_projects')->onDelete('cascade')->onUpdate('cascade');
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_project_users');
    }
};
