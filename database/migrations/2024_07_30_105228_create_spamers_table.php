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
    if (!Schema::hasTable('spammers')) {
        Schema::create('spammers', function (Blueprint $table) {
            $table->id();
            
            // Внешний ключ для пользователя, который помечен как спамер
            $table->foreignId('id_user')
                ->constrained('users')   // Связь с таблицей users
                ->onDelete('cascade');   // При удалении пользователя удаляются все его записи как спамера

            // Количество штрафных очков за спам (будет увеличиваться с каждым новым инцидентом)
            $table->integer('penalty')->default(1); // Начальный штраф - 1

            // Временные метки
            $table->timestamps();
        });
    }
}



    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('spamers');
    }
};
