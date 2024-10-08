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
        if (!Schema::hasTable('comments_news')) {
            Schema::create('comments_news', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->integer('new_id');
                $table->string('author');
                $table->text('text');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments_news');
    }
};
