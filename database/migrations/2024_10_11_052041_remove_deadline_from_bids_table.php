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
            $table->date('deadline')->nullable()->change(); // Делаем поле deadline необязательным
        });
    }
    
    // public function down()
    // {
    //     Schema::table('bids', function (Blueprint $table) {
    //         $table->date('deadline')->nullable(false)->change(); // Возвращаем обязательное поле (в случае отката)
    //     });
    // }
    

};
