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
        Schema::create('trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 100);
            $table->string('display_name', 200);
            $table->string('icon', 10);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('trackables', function (Blueprint $table) {
            $table->foreignId('tracker_id')->constrained();
            $table->unsignedBigInteger('trackable_id');
            $table->string('trackable_type');

            $table->unique(['tracker_id', 'trackable_id', 'trackable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trackables');
        Schema::dropIfExists('trackers');
    }
};
