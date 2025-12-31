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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('kode')->unique(); // R-001, R-002, etc.
            $table->string('unit'); // Hospital unit/department
            $table->string('kategori'); // Strategis, Operasional, Fraud, etc.
            $table->text('risiko'); // Risk description
            $table->text('dampak_deskripsi')->nullable(); // Impact description
            $table->text('penyebab')->nullable(); // Cause
            
            // Initial scores (1-5)
            $table->tinyInteger('awal_d')->default(1); // Initial impact
            $table->tinyInteger('awal_p')->default(1); // Initial probability
            
            $table->text('pengendalian')->nullable(); // Control measures
            $table->string('evaluasi')->default('Dibagi'); // Evaluation type
            
            // Residual scores (1-5)
            $table->tinyInteger('residual_d')->default(1); // Residual impact
            $table->tinyInteger('residual_p')->default(1); // Residual probability
            
            $table->string('pj')->nullable(); // Person in charge
            $table->enum('status', ['Not Started', 'In-Progress', 'Completed'])->default('Not Started');
            $table->enum('validasi', ['Menunggu', 'Valid', 'Revisi'])->default('Menunggu');
            $table->string('validator')->nullable(); // Validator name
            
            $table->string('triwulan')->nullable(); // Period (e.g., "Triwulan II")
            $table->integer('period_year')->default(2024); // Year
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
