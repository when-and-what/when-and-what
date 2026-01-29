<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('podcast_episode_plays');
        Schema::dropIfExists('podcast_episode_ratings');
        Schema::dropIfExists('podcast_episodes');
        Schema::dropIfExists('podcasts');

        Schema::create('podcasts', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->string('title');
            $table->text('description')->default('');
            $table->string('url')->nullable()->default(null);
            $table->string('image')->nullable()->default(null);
            $table->string('author')->nullable()->default(null);
            $table->boolean('is_private')->default(false);
            $table->timestamps();
        });

        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('podcast_id');
            $table->string('title');
            $table->text('description')->nullable()->default(null);
            $table->datetime('published_at')->nullable()->default(null);
            $table->integer('duration')->nullable()->default(null);
            $table->integer('season')->nullable()->default(null);
            $table->integer('episode')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('podcast_episode_plays', function (Blueprint $table) {
            $table->id();
            $table->uuid('episode_id');
            $table->foreignId('user_id')->constrained();
            $table->date('play_date');
            $table->integer('seconds');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_episode_plays');
        Schema::dropIfExists('podcast_episodes');
        Schema::dropIfExists('podcasts');
    }
};
