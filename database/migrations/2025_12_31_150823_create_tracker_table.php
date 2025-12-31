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
        Schema::create('trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('name');
            $table->string('code');
            $table->string('type')->default('NUMBER');
            $table->string('unit', 10)->nullable()->default(NULL);
            $table->string('icon', 100)->nullable()->default(NULL);
            $table->string('color', 7)->default('#000000');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tracker_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracker_id');
            $table->decimal('event_value', 10, 4);
            $table->dateTime('event_time');
            $table->boolean('all_day')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackers');
        Schema::dropIfExists('tracker_events');
    }
};
