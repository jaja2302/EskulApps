<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('eskuls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image');
            $table->string('banner_image')->nullable();
            $table->foreignId('pelatih_id')->constrained('users');
            $table->foreignId('pembina_id')->nullable()->constrained('users');
            $table->integer('quota')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('meeting_location')->nullable();
            $table->text('requirements')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eskuls');
    }
};