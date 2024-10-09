<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('discussion')) {
            Schema::create('discussion', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_offer');
                $table->unsignedBigInteger('id_user');
                $table->boolean('vote'); // true (1) for ready to vote, false (0) otherwise
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('discussion');
    }
}
