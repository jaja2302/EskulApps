<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('participation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('recorded_by')->constrained('users'); // Pelatih yang mencatat
            $table->enum('activity_type', [
                'discussion',      // Aktif diskusi
                'practice',        // Latihan
                'presentation',    // Presentasi
                'volunteering',    // Sukarela membantu
                'leadership',      // Memimpin kegiatan
                'other'           // Lainnya
            ]);
            $table->text('description');
            $table->integer('points')->default(0); // Poin partisipasi (1-100)
            $table->date('activity_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participation_records');
    }
}; 