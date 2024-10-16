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
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_like'); // true - лайк, false - дизлайк
            $table->timestamps();

            $table->unique(['task_id', 'user_id']); // Запрещает одному пользователю голосовать несколько раз за одну задачу
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_votes');
    }
}
