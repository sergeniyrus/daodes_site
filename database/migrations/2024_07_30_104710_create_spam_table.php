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
        if (!Schema::hasTable('spam')) {
            Schema::create('spam', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_offer')->constrained('offers')->onDelete('cascade');
                $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
                $table->boolean('vote'); // 0 - не спам, 1 - спам
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('spam');
    }
};
