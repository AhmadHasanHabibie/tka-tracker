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
        Schema::create('utbk_subtest_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utbk_tryout_id')->constrained('utbk_tryouts')->onDelete('cascade');
            $table->string('subtest');
            $table->decimal('score', 7, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utbk_subtest_scores');
    }
};
