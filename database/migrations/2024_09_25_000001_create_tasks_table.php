<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up(): void
{
    // Проверка существования таблицы task_categories перед её созданием
    if (!Schema::hasTable('task_categories')) {
        Schema::create('task_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index();  // Название категории, уникальное и индексированное
            $table->timestamps();

            // Добавление индекса для поля 'name' для улучшения производительности поиска
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
            $table->foreignId('category_id')->constrained('task_categories'); // Связь с таблицей категорий
            
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
}



    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
