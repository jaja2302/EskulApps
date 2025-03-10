<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eskul_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eskul_id')->constrained('eskuls');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('media_type'); // image/video
            $table->text('file_path');
            $table->date('event_date');
            $table->string('title_video')->nullable();
            $table->text('description_video')->nullable();
            $table->string('link_video')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eskul_galleries');
    }
}; 