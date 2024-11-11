<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    public function up()
{
    Schema::create('user_profiles', function (Blueprint $table) {
        $table->id();
        
        // Внешний ключ для связи с таблицей users
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Основные данные профиля
        $table->enum('role', ['executor', 'customer', 'both'])->default('customer');
        
        // Контактные данные
        $table->string('avatar_url')->nullable();
        $table->string('nickname')->nullable()->index(); // Индекс для быстрого поиска по нику
        $table->enum('gender', ['male', 'female'])->nullable(); // Ограничиваем поле гендера
        $table->string('timezone')->nullable();
        $table->text('languages')->nullable();
        
        // Профессиональные и личные данные
        $table->date('birth_date')->nullable();
        $table->text('education')->nullable();
        $table->text('resume')->nullable();
        $table->text('portfolio')->nullable();
        $table->text('specialization')->nullable();
        
        // Репутация и рейтинг
        $table->decimal('rating', 5, 2)->default(0);
        $table->decimal('trust_level', 5, 2)->default(0);
        $table->decimal('sbt_tokens', 5, 2)->default(0);
        
        // Задания и рекомендации
        $table->integer('tasks_completed')->default(0);
        $table->integer('tasks_failed')->default(0);
        $table->json('recommendations')->nullable();
        
        // Активность и достижения
        $table->json('activity_log')->nullable();
        $table->json('achievements')->nullable();
        
        // Временные метки
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}

