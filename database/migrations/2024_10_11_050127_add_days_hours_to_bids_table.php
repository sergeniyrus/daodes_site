<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('bids', function (Blueprint $table) {
        $table->integer('days')->default(0);  // Поле для дней
        $table->integer('hours')->default(0); // Поле для часов
    });
}

public function down()
{
    Schema::table('bids', function (Blueprint $table) {
        $table->dropColumn('days');
        $table->dropColumn('hours');
    });
}

};
