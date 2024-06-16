<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNicknameFromWalletsTable extends Migration
{
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn('nickname');
        });
    }

    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->string('nickname')->nullable();
        });
    }
}
