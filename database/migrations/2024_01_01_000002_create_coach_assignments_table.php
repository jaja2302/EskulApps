<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coach_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->enum('role', ['pelatih', 'wakil_pelatih']);
            $table->timestamps();

            $table->unique(['user_id', 'eskul_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coach_assignments');
    }
}; 