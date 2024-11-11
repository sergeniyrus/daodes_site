<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidsTable extends Migration
{
    public function up(): void
{
    // Создание таблицы bids с добавлением всех необходимых полей
    Schema::create('bids', function (Blueprint $table) {
        $table->id();
        
        // Внешние ключи
        $table->foreignId('task_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Поля для цены, дедлайна и комментария
        $table->decimal('price', 10, 2);
        $table->date('deadline')->nullable(); // Делаем поле deadline необязательным
        $table->text('comment')->nullable();
        
        // Дополнительные поля для дней и часов
        $table->integer('days')->default(0); // Поле для дней
        $table->integer('hours')->default(0); // Поле для часов

        // Временные метки
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('bids');
    }
}
