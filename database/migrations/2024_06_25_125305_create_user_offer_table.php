<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_offer');
            $table->unsignedBigInteger('id_user');
            $table->integer('vote')->comment('1 = За, 2 = Против, 3 = Воздержался');
            $table->timestamps();

            $table->foreign('id_offer')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_offer');
    }
}
