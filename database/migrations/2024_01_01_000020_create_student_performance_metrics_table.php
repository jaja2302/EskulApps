<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->integer('year');
            $table->integer('semester');
            $table->decimal('attendance_score', 5, 2);
            $table->decimal('participation_score', 5, 2);
            $table->decimal('achievement_score', 5, 2);
            $table->integer('cluster')->nullable();
            $table->timestamps();
            
            $table->unique(
                ['student_id', 'eskul_id', 'year', 'semester'],
                'metrics_student_period_unique'
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_performance_metrics');
    }
}; 