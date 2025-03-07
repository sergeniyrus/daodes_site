<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID для идентификатора
            $table->string('type'); // Тип уведомления
            $table->morphs('notifiable'); // Добавляет notifiable_type и notifiable_id
            $table->text('data'); // Данные уведомления
            $table->timestamp('read_at')->nullable(); // Время прочтения
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
