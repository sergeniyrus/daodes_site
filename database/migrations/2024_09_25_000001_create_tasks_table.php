<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up(): void
    {
        // Проверка существования таблицы category_tasks перед её созданием
        if (!Schema::hasTable('category_tasks')) {
            Schema::create('category_tasks', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique()->index();  // Название категории, уникальное и индексированное
                $table->timestamps();
            });
        }

        // Проверка существования таблицы tasks перед её созданием
        if (!Schema::hasTable('tasks')) {
            // Создание таблицы tasks со всеми необходимыми полями
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->decimal('budget', 10, 2);
                $table->date('deadline')->nullable(); // Добавлено nullable для поддержки изменений
                $table->string('status')->default('open');

                // Внешние ключи
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('category_id')->nullable()->constrained('category_tasks')->onDelete('cascade'); // Связь с таблицей категорий

                // Лайки и дизлайки
                $table->integer('likes')->default(0);      // Поле для лайков
                $table->integer('dislikes')->default(0);   // Поле для дизлайков

                // Дополнительные поля
                $table->unsignedBigInteger('accepted_bid_id')->nullable(); // Поле для хранения ID принятого предложения
                $table->timestamp('start_time')->nullable(); // Время начала работы
                $table->boolean('in_progress')->default(false); // Статус задачи: в процессе или нет
                $table->boolean('completed')->default(false);  // Статус задачи: завершена или нет
                $table->timestamp('completion_time')->nullable(); // Время завершения задачи
                $table->integer('rating')->nullable(); // Рейтинг задачи
                $table->timestamp('completed_at')->nullable(); // Время завершения задачи

                // Временные метки
                $table->timestamps();
            });
        }

        // Создание таблицы task_votes
        if (!Schema::hasTable('task_votes')) {
            Schema::create('task_votes', function (Blueprint $table) {
                $table->id();

                // Внешние ключи с каскадным удалением
                $table->foreignId('task_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                // Поле для хранения информации о голосе
                $table->boolean('is_like')->default(true); // true - лайк, false - дизлайк (по умолчанию лайк)

                // Временные метки
                $table->timestamps();

                // Уникальное ограничение на комбинацию task_id и user_id
                $table->unique(['task_id', 'user_id']); // Запрещает одному пользователю голосовать несколько раз за одну задачу

                // Добавление индекса для ускорения поиска по task_id и user_id
                $table->index(['task_id', 'user_id']);
            });
        }
    }

    public function down(): void
    {
        // Удаление таблиц в правильном порядке
        Schema::dropIfExists('task_votes');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('category_tasks');
    }
}
