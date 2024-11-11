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
    // Проверка существования таблицы перед её созданием
    if (!Schema::hasTable('seed')) {
        Schema::create('seed', function (Blueprint $table) {
            $table->id();

            // Внешний ключ для пользователя (user_id)
            $table->foreignId('user_id')
                ->constrained('users') // Указывает на таблицу users
                ->onDelete('cascade')
                ->index(); // Индекс для ускорения поиска по user_id

            // Хешированные слова (word0, word1, ..., word23)
            $table->string('word0')->nullable();
            $table->string('word1')->nullable();
            $table->string('word2')->nullable();
            $table->string('word3')->nullable();
            $table->string('word4')->nullable();
            $table->string('word5')->nullable();
            $table->string('word6')->nullable();
            $table->string('word7')->nullable();
            $table->string('word8')->nullable();
            $table->string('word9')->nullable();
            $table->string('word10')->nullable();
            $table->string('word11')->nullable();
            $table->string('word12')->nullable();
            $table->string('word13')->nullable();
            $table->string('word14')->nullable();
            $table->string('word15')->nullable();
            $table->string('word16')->nullable();
            $table->string('word17')->nullable();
            $table->string('word18')->nullable();
            $table->string('word19')->nullable();
            $table->string('word20')->nullable();
            $table->string('word21')->nullable();
            $table->string('word22')->nullable();
            $table->string('word23')->nullable();

            // Добавление временных меток (created_at, updated_at)
            $table->timestamps();
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seed');
    }
};
