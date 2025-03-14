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
              $table->string('name')->after('id'); // Добавляем столбец `name`
              $table->string('type')->default('personal')->after('name'); // Добавляем поле type
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
                $table->uuid('id')->primary(); // UUID для идентификатора
                $table->string('type'); // Тип уведомления
                $table->morphs('notifiable'); // Добавляет notifiable_type и notifiable_id
                $table->text('data'); // Данные уведомления
                $table->timestamp('read_at')->nullable(); // Время прочтения
                $table->timestamps();
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
