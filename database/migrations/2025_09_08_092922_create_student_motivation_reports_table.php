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
        Schema::create('student_motivation_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('eskul_id')->constrained('eskuls')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('year');
            $table->integer('semester');
            $table->integer('month')->nullable();
            $table->integer('cluster')->default(2);
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('participation_score', 5, 2)->default(0);
            $table->decimal('achievement_score', 5, 2)->default(0);
            $table->text('motivation_reason')->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'action_taken'])->default('pending');
            $table->text('action_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['student_id', 'eskul_id', 'year', 'semester'], 'smr_student_eskul_period_idx');
            $table->index(['cluster', 'status'], 'smr_cluster_status_idx');
            $table->index(['created_by', 'year', 'semester'], 'smr_creator_period_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_motivation_reports');
    }
};
