<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('playlist_song', function (Blueprint $table) {

            $table->id();

            $table->foreignId('playlist_id')
                ->constrained('playlists')
                ->cascadeOnDelete();

            $table->foreignId('song_id')
                ->constrained('songs')
                ->cascadeOnDelete();

            // กัน duplicate (เพลงเดียวกันใน playlist เดียวซ้ำ)
            $table->unique(['playlist_id', 'song_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_song');
    }
};
