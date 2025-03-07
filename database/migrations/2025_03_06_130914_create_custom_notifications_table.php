<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Автоинкрементный ID
            $table->unsignedBigInteger('user_id'); // Связь с пользователем
            $table->unsignedBigInteger('message_id'); // Связь с сообщением
            $table->boolean('is_read')->default(false); // Статус прочтения
            $table->timestamps();

            // Внешние ключи
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}