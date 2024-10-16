<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('budget', 10, 2);
            $table->date('deadline');
            $table->string('status')->default('open');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('task_categories'); // Связь с таблицей категорий
            $table->integer('likes')->default(0);      // Поле для лайков
            $table->integer('dislikes')->default(0);   // Поле для дизлайков
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
