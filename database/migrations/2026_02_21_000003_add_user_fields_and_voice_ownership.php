<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('elevenlabs_api_key')->nullable()->after('password');
            $table->text('fish_audio_api_key')->nullable()->after('elevenlabs_api_key');
        });

        Schema::table('voices', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['elevenlabs_api_key', 'fish_audio_api_key']);
        });

        Schema::table('voices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
