<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('eskul_tests')->onDelete('cascade');
            $table->enum('question_type', ['multiple_choice', 'essay', 'file_upload']);
            $table->text('question');
            $table->text('options')->nullable(); // JSON untuk pilihan ganda
            $table->string('correct_answer')->nullable(); // Untuk pilihan ganda
            $table->integer('score_weight')->default(1);
            $table->text('answer_explanation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_questions');
    }
}; 