<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests');
            $table->foreignId('student_id')->constrained('users');
            $table->integer('score');
            $table->timestamps();

            $table->unique(['test_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
}; 