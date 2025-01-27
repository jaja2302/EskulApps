<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('eskul_tests');
            $table->foreignId('student_id')->constrained('users');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->integer('score')->nullable();
            $table->boolean('is_passed')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_attempts');
    }
}; 