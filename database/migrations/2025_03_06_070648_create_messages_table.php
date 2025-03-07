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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('chat_id');
        $table->unsignedBigInteger('sender_id');
        $table->text('encrypted_message'); // Зашифрованный текст
        $table->string('ipfs_cid'); // CID контента в IPFS
        $table->boolean('is_read')->default(false); // Прочитано ли сообщение
        $table->timestamps();

        $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
