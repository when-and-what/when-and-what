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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('published_at');
            $table->boolean('dashboard_visible')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['published_at', 'dashboard_visible']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notes');
    }
};
