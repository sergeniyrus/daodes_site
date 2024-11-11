<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    if (!Schema::hasTable('offer_votes')) {
        Schema::create('offer_votes', function (Blueprint $table) {
            $table->id();
            
            // Внешние ключи
            $table->foreignId('id_offer')
                ->constrained('offers') // Ссылается на таблицу offers
                ->onDelete('cascade');  // При удалении предложения удаляются голоса

            $table->foreignId('id_user')
                ->constrained('users')  // Ссылается на таблицу users
                ->onDelete('cascade');  // При удалении пользователя удаляются голоса

            // Голосование
            $table->integer('vote')
                ->comment('1 = За, 2 = Против, 3 = Воздержался');

            // Временные метки
            $table->timestamps();

            // Уникальный индекс для комбинации id_offer и id_user
            $table->unique(['id_offer', 'id_user']);  // Каждый пользователь может голосовать только один раз за одно предложение
        });
    }
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_offer');
    }
}
