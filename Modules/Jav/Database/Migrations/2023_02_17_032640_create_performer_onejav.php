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
        Schema::create('performer_onejav', function (Blueprint $table) {
            $table->id();

           $table->unsignedBigInteger('performer_id')->index();
           $table->foreign('performer_id')->references('id')->on('performers')->onDelete('cascade');

           $table->unsignedBigInteger('onejav_id')->index();
           $table->foreign('onejav_id')->references('id')->on('onejav')->onDelete('cascade');

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
        //
    }
};
