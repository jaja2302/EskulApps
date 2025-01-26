<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop existing tables first
        Schema::dropIfExists('attendance_details');
        Schema::dropIfExists('attendances');

        // Create new attendances table
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('created_by')->constrained('users'); // Pelatih yang membuat
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->text('activity_description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft');
            $table->timestamps();
        });

        // Create new attendance_details table
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->time('check_in_time')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable(); // Untuk surat izin/sakit
            $table->foreignId('verified_by')->nullable()->constrained('users'); // Pelatih yang verifikasi
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['attendance_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_details');
        Schema::dropIfExists('attendances');
    }
}; 