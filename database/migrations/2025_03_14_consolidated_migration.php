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
        if (!Schema::hasTable('chats')) {
            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Убираем метод after
                $table->string('type')->default('personal'); // Убираем метод after
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('chat_id');
                $table->unsignedBigInteger('sender_id');
                $table->string('ipfs_cid'); // CID контента в IPFS
                $table->timestamps();

                $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id(); // Автоинкрементный ID
                $table->unsignedBigInteger('user_id'); // Убедитесь, что тип данных совпадает с `users`.`id`
                $table->unsignedBigInteger('message_id');
                $table->boolean('is_read')->default(false);
                $table->timestamps();

                // Внешние ключи
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('chat_user')) {
            Schema::create('chat_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('chat_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_user');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chats');
    }
};
