<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informasi Pribadi
            $table->string('nis')->nullable()->unique();  // Nomor Induk Siswa
            $table->string('nisn')->nullable()->unique(); // Nomor Induk Siswa Nasional
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('religion')->nullable();
            
            // Informasi Akademik
            $table->string('class')->nullable();          // Kelas saat ini
            $table->string('academic_year')->nullable();  // Tahun akademik
            
            // Informasi Orang Tua/Wali
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_phone')->nullable();
            
            // Informasi Wali (opsional)
            $table->string('guardian_name')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_relation')->nullable(); // Hubungan dengan siswa
            
            // Informasi Tambahan
            $table->text('medical_history')->nullable();    // Riwayat kesehatan
            $table->text('special_needs')->nullable();      // Kebutuhan khusus
            $table->text('notes')->nullable();             // Catatan tambahan
            
            $table->timestamps();
            $table->softDeletes(); // Untuk fitur "trash"
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}; 