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
        if (!Schema::hasTable('history_pays')) {
            Schema::create('history_pays', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('from_wallet_id');
                $table->unsignedBigInteger('to_wallet_id');
                $table->decimal('amount', 18, 8);
                $table->decimal('fee', 18, 8);
                $table->timestamps();
        
                $table->foreign('from_wallet_id')->references('id')->on('wallets')->onDelete('cascade');
                $table->foreign('to_wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pays');
    }
};
