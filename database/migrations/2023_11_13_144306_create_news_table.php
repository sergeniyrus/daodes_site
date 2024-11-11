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
    // Проверка существования таблицы category_news перед её созданием
    if (!Schema::hasTable('category_news')) {
        Schema::create('category_news', function (Blueprint $table) {
            $table->id();

            // Название категории, ограниченное 255 символами, уникальное
            $table->string('category_name', 255)->unique();

            // Добавление временных меток (created_at, updated_at)
            $table->timestamps();
        });
    }

    // Проверка существования таблицы news перед её созданием
    if (!Schema::hasTable('news')) {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Название новости, ограниченное 255 символами
            $table->string('title', 255);

            // Содержимое статьи
            $table->text('content');

            // Ссылка на изображение
            $table->string('img')->nullable(); // Поле может быть пустым, если изображение не указано

            // Внешний ключ для категории новости
            $table->foreignId('category_id')
                ->constrained('category_news')
                ->onDelete('cascade')
                ->index()
                ->name('fk_news_category_id'); // Уникальное имя для внешнего ключа

            // Внешний ключ для пользователя, создавшего новость
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->index()
                ->name('fk_news_user_id'); // Уникальное имя для внешнего ключа

            // Число просмотров новости, по умолчанию 0
            $table->unsignedInteger('views')->default(0);
        });
    }

    // Проверка существования таблицы comments_news перед её созданием
    if (!Schema::hasTable('comments_news')) {
        Schema::create('comments_news', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            // Внешний ключ для новости (new_id)
            $table->foreignId('new_id')
                ->constrained('news') // Указывает на таблицу news
                ->onDelete('cascade')
                ->index()
                ->name('fk_comments_news_new_id'); // Уникальное имя для внешнего ключа

            // Внешний ключ для пользователя (user_id)
            $table->foreignId('user_id')
                ->constrained('users') // Указывает на таблицу users
                ->onDelete('cascade')
                ->index()
                ->name('fk_comments_news_user_id'); // Уникальное имя для внешнего ключа

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
        Schema::dropIfExists('news');
    }
};
