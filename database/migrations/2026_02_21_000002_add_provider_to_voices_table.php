<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voices', function (Blueprint $table) {
            $table->string('provider')->default('elevenlabs')->after('elevenlabs_voice_id');
        });
    }

    public function down(): void
    {
        Schema::table('voices', function (Blueprint $table) {
            $table->dropColumn('provider');
        });
    }
};
