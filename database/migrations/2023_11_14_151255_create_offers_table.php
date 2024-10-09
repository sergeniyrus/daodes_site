<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('title');
                $table->text('text');
                $table->string('img');
                $table->integer('category_id');
                $table->string('author');
                $table->integer('views');
                $table->string('state');
                $table->string('method');
                $table->string('budget');
                $table->string('coin');
                $table->date('control');
                $table->date('finish');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
