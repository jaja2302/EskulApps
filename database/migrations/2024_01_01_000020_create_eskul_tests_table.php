<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eskul_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id')->constrained();
            $table->foreignId('created_by')->constrained('users'); // Pelatih yang membuat
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('duration_minutes');
            $table->integer('passing_score')->default(70);
            $table->boolean('is_active')->default(false);
            $table->enum('test_type', ['practice', 'theory', 'both']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eskul_tests');
    }
}; 