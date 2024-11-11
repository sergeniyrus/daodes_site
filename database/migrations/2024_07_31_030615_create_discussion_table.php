<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionTable extends Migration
{
    public function up()
{
    // Проверка существования таблицы "discussions"
    if (!Schema::hasTable('discussions')) {
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            
            // Внешний ключ для связи с таблицей offers
            $table->foreignId('offer_id')
                ->constrained('offers')  // Связь с таблицей offers
                ->onDelete('cascade');   // При удалении предложения удаляются все его обсуждения

            // Внешний ключ для связи с таблицей users
            $table->foreignId('user_id')
                ->constrained('users')   // Связь с таблицей users
                ->onDelete('cascade');   // При удалении пользователя удаляются все его обсуждения

            // Поле для голосования (true - готов к голосованию, false - нет)
            $table->boolean('vote')->default(false) // По умолчанию false (не готов к голосованию)
                ->comment('true (1) for ready to vote, false (0) otherwise');

            // Временные метки
            $table->timestamps();

            // Уникальный индекс на сочетание offer_id и user_id
            $table->unique(['offer_id', 'user_id']);  // Чтобы один пользователь мог создать только одно обсуждение на одно предложение

            // Индексы для быстрого поиска
            // Если индексы на 'offer_id' и 'user_id' не обязательны, их можно удалить
        });
    }
}



    public function down()
    {
        Schema::dropIfExists('discussion');
    }
}
