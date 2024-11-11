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
    // Проверка существования таблицы category_offers перед её созданием
    if (!Schema::hasTable('category_offers')) {
        Schema::create('category_offers', function (Blueprint $table) {
            $table->id();

            // Название категории, ограниченное 255 символами, уникальное
            $table->string('category_name', 255)->unique();

            // Добавление временных меток (created_at, updated_at)
            $table->timestamps();
        });
    }

    // Проверка существования таблицы offers перед её созданием
    if (!Schema::hasTable('offers')) {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Заголовок предложения, ограниченное 255 символами
            $table->string('title', 255);

            // Основной текст предложения
            $table->text('text');

            // Ссылка на изображение, необязательное поле
            $table->string('img')->nullable();

            // Внешний ключ для категории предложения
            $table->foreignId('category_id')
                ->constrained('category_offers')
                ->onDelete('cascade')
                ->index()
                ->name('fk_offers_category_id'); // Уникальное имя для внешнего ключа

            // Внешний ключ для пользователя, создавшего предложение
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->index()
                ->name('fk_offers_user_id'); // Уникальное имя для внешнего ключа

            // Число просмотров предложения, по умолчанию 0
            $table->unsignedInteger('views')->default(0);

            // Состояние предложения, ограниченное 50 символами
            $table->string('state', 50);

            // Метод предложения, ограниченный 50 символами
            $table->string('method', 50);

            // Бюджет предложения, ограниченный 100 символами
            $table->string('budget', 100);

            // Валюта предложения, ограниченная 10 символами
            $table->string('coin', 10);

            // Дата контроля предложения
            $table->date('control');

            // Дата завершения предложения
            $table->date('finish');
        });
    }

    // Проверка существования таблицы comments_offers перед её созданием
    if (!Schema::hasTable('comments_offers')) {
        Schema::create('comments_offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Внешний ключ для предложения (offer_id)
            $table->foreignId('offer_id')
                ->constrained('offers') // Указывает на таблицу offers
                ->onDelete('cascade')
                ->index()
                ->name('fk_comments_offers_offer_id'); // Уникальное имя для внешнего ключа

            // Внешний ключ для пользователя (user_id)
            $table->foreignId('user_id')
                ->constrained('users') // Указывает на таблицу users
                ->onDelete('cascade')
                ->index()
                ->name('fk_comments_offers_user_id'); // Уникальное имя для внешнего ключа

            // Текст комментария, nullable, так как не всегда обязателен
            $table->text('text')->nullable();
        });
    }
}




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
