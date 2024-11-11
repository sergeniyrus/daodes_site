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
    // Проверка существования таблицы "wallets" до её создания
    if (!Schema::hasTable('wallets')) {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            // Внешний ключ для пользователя (user_id)
            $table->foreignId('user_id')
                ->constrained('users') // Связывает с таблицей users
                ->onDelete('cascade')  // При удалении пользователя удаляются все его кошельки
                ->index('idx_wallets_user_id'); // Уникальное имя для индекса

            // Баланс пользователя в кошельке
            $table->decimal('balance', 18, 8)
                ->default(100)  // Значение по умолчанию для нового кошелька
                ->unsigned()    // Баланс не может быть отрицательным
                ->comment('The balance of the user wallet.'); // Комментарий, поясняющий поле

            // Временные метки для отслеживания времени создания и обновления
            $table->timestamps();
        });
    }

    // Проверка существования таблицы "history_pays" до её создания
    if (!Schema::hasTable('history_pays')) {
        Schema::create('history_pays', function (Blueprint $table) {
            $table->id();

            // Идентификаторы кошельков отправителя и получателя
            $table->foreignId('from_wallet_id')
                ->constrained('wallets') // Связывает с таблицей wallets
                ->onDelete('cascade')    // При удалении кошелька удаляются связанные транзакции
                ->index('idx_history_pays_from_wallet'); // Индекс для ускорения запросов

            $table->foreignId('to_wallet_id')
                ->constrained('wallets') // Связывает с таблицей wallets
                ->onDelete('cascade')    // При удалении кошелька удаляются связанные транзакции
                ->index('idx_history_pays_to_wallet'); // Индекс для ускорения запросов

            // Сумма транзакции и комиссия
            $table->decimal('amount', 18, 8)
                ->comment('The amount of money transferred between wallets.');

            $table->decimal('fee', 18, 8)
                ->comment('The transaction fee applied to the transfer.');

            // Временные метки для отслеживания времени создания и обновления записи
            $table->timestamps();
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
