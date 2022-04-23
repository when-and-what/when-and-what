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
        Schema::create('location_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name')->unique();
            $table->string('emoji')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->decimal('latitude', 20, 18);
            $table->decimal('longitude', 21, 18);
            $table->string('address', 250)->nullable();
            $table->string('city', 250)->nullable();
            $table->string('state', 250)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 250)->nullable();
            $table->text('website')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->datetime('checkin_at');
            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('pending_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('latitude', 20, 18);
            $table->decimal('longitude', 21, 18);
            $table->datetime('checkin_at');
            $table->string('name')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('pending_checkins');
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('location_categories');
    }
};
