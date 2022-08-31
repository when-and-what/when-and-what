<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nickname');
            $table->string('image', 500)->nullable()->default(null);
            $table->string('website')->nullable()->default(null);
            $table->string('feed')->nullable()->default(null);
            $table->foreignId('created_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['name', 'created_by']);
        });

        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained();
            $table->string('name');
            $table->datetime('published_at')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->integer('duration')->nullable()->default(null);
            $table->integer('season')->nullable()->default(null);
            $table->integer('episode')->nullable()->default(null);
            $table->boolean('imported')->default(false);
            $table->foreignId('created_by')->nullable()->default(null)->constrained('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('podcast_episode_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('podcast_episodes');
            $table->foreignId('user_id')->constrained();
            $table->tinyInteger('rating')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('podcast_episode_plays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('podcast_episodes');
            $table->foreignId('user_id')->constrained();
            $table->dateTime('played_at');
            $table->integer('seconds');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('podcast_episode_plays');
        Schema::dropIfExists('podcast_episode_ratings');
        Schema::dropIfExists('podcast_episodes');
        Schema::dropIfExists('podcasts');
    }
};
