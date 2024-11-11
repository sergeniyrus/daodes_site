<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskVotesTable extends Migration
{
    public function up()
{
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


    public function down()
    {
        Schema::dropIfExists('task_votes');
    }
}
