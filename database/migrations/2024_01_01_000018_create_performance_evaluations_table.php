<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('evaluated_by')->constrained('users');
            $table->enum('evaluation_type', [
                'monthly',     // Evaluasi bulanan
                'semester',    // Evaluasi semester
                'yearly'      // Evaluasi tahunan
            ]);
            $table->integer('skill_score');        // Nilai keterampilan (1-100)
            $table->integer('knowledge_score');    // Nilai pengetahuan (1-100)
            $table->integer('attitude_score');     // Nilai sikap (1-100)
            $table->text('achievements')->nullable();  // Pencapaian khusus
            $table->text('improvements')->nullable();  // Area pengembangan
            $table->text('notes')->nullable();
            $table->date('evaluation_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('performance_evaluations');
    }
}; 