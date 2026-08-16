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
        Schema::create('tka_subject_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tka_tryout_id')->constrained('tka_tryouts')->onDelete('cascade');
            $table->foreignId('tka_subject_id')->nullable()->constrained('tka_subjects')->onDelete('set null');
            $table->string('subject_name');
            $table->enum('subject_type', ['mandatory', 'choice'])->default('mandatory');
            $table->decimal('score', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tka_subject_scores');
    }
};
