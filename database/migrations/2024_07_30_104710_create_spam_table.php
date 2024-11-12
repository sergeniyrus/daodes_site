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
    if (!Schema::hasTable('spam')) {
        Schema::create('spam', function (Blueprint $table) {
            $table->id();
            
            // Внешние ключи для предложения и пользователя
            $table->foreignId('offer_id')
                ->constrained('offers')  // Связь с таблицей offers
                ->onDelete('cascade');   // При удалении предложения удаляются все его отметки как спам

            $table->foreignId('user_id')
                ->constrained('users')   // Связь с таблицей users
                ->onDelete('cascade');   // При удалении пользователя удаляются все его отметки как спам

            // Голосование за спам (0 - не спам, 1 - спам)
            $table->boolean('vote')    // Можно оставить boolean или tinyInteger(1)
                ->comment('1 = Спам, 0 = Не спам');

            // Временные метки
            $table->timestamps();

            // Уникальный индекс на сочетание id_offer и id_user, чтобы каждый пользователь мог отметить только один раз
            $table->unique(['offer_id', 'user_id']);
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('spam');
    }
};
