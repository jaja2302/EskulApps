<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eskul_event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('eskul_events')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users');
            $table->string('status')->default('registered'); // registered, attended, absent
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eskul_event_participants');
    }
}; 