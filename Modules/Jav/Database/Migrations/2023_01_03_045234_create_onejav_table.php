<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onejav', function (Blueprint $table) {
            $table->id();

            $table->string('url')->unique();
            $table->string('cover')->nullable();
            $table->string('dvd_id')->nullable()->index();
            $table->unsignedFloat('size')->nullable();
            $table->date('date')->nullable();
            $table->json('genres')->nullable();
            $table->string('description')->nullable();
            $table->json('performers')->nullable();
            $table->string('torrent')->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('onejav');
    }
};
