<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNicknameHashToNicknameInWalletsTable extends Migration
{
    public function up()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->renameColumn('nickname_hash', 'nickname');
        });
    }

    public function down()
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->renameColumn('nickname', 'nickname_hash');
        });
    }
}
