<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('test_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('test_questions');
            $table->text('answer');
            $table->string('file_path')->nullable(); // Untuk jawaban berupa file
            $table->integer('score')->nullable(); // Untuk essay/file yang perlu dinilai manual
            $table->text('feedback')->nullable();
            $table->foreignId('scored_by')->nullable()->constrained('users');
            $table->datetime('scored_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_answers');
    }
}; 