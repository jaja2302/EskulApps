<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('eskuls', function (Blueprint $table) {
            $table->integer('quota')->nullable()->after('wakil_pelatih_id');
            $table->boolean('is_active')->default(true)->after('quota');
            $table->string('meeting_location')->nullable()->after('is_active');
            $table->text('requirements')->nullable()->after('meeting_location');
            $table->string('category')->nullable()->after('requirements');
            $table->string('banner_image')->nullable()->after('image');
        });
    }

    public function down()
    {
        Schema::table('eskuls', function (Blueprint $table) {
            $table->dropColumn([
                'quota',
                'is_active',
                'meeting_location',
                'requirements',
                'category',
                'banner_image'
            ]);
        });
    }
}; 