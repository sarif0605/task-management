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
        Schema::create('deal_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prospect_id');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date');
            $table->decimal('harga_deal', 15, 2);
            $table->text('keterangan');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_projects');
    }
};
