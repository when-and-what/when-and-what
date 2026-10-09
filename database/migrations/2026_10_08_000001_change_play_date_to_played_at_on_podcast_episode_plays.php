<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('podcast_episode_plays', function (Blueprint $table) {
            $table->dateTime('play_date')->change();
        });

        Schema::table('podcast_episode_plays', function (Blueprint $table) {
            $table->renameColumn('play_date', 'played_at');
        });
    }

    public function down(): void
    {
        Schema::table('podcast_episode_plays', function (Blueprint $table) {
            $table->renameColumn('played_at', 'play_date');
        });

        Schema::table('podcast_episode_plays', function (Blueprint $table) {
            $table->date('play_date')->change();
        });
    }
};
