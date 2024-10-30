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
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->string('title');
                $table->text('content');
                $table->string('img');
                $table->foreignId('category_id')->constrained('category_news')->onDelete('cascade'); // Изменено на foreignId
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Исправлено на user_id
                $table->integer('views')->default(0); // Добавлено значение по умолчанию
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
