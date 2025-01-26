<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eskul_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('added_by')->constrained('users'); // Admin/Pelatih yang menambahkan
            $table->boolean('is_active')->default(true);
            $table->date('join_date');
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'eskul_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('eskul_members');
    }
}; 