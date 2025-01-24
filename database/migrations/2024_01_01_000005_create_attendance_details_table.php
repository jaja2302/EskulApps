<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances');
            $table->foreignId('student_id')->constrained('users');
            $table->enum('status', ['hadir', 'izin', 'alpa']);
            $table->timestamps();

            $table->unique(['attendance_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_details');
    }
}; 