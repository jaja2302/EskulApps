<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('student_id')->constrained('users');
            $table->string('title');
            $table->text('description');
            $table->date('achievement_date');
            $table->string('level'); // sekolah/kota/provinsi/nasional/internasional
            $table->string('position'); // juara 1/2/3/harapan
            $table->string('certificate_file')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('achievements');
    }
}; 