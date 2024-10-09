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
        if (!Schema::hasTable('comments_offers')) {
            Schema::create('comments_offers', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->integer('offer_id');
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
        Schema::dropIfExists('comments_offers');
    }
};
