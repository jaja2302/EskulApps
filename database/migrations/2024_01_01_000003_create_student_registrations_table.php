<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'eskul_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_registrations');
    }
}; 