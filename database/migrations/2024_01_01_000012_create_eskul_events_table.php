<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eskul_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('created_by')->constrained('users');
            $table->string('title');
            $table->text('description');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('location');
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_winner_announced')->default(false);
            $table->integer('quota')->nullable();
            $table->boolean('requires_registration')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eskul_events');
    }
}; 