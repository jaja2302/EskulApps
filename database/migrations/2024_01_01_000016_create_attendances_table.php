<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained('eskul_schedules')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users');
            $table->date('date');
            $table->datetime('check_in_time');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->datetime('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['schedule_id', 'student_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}; 