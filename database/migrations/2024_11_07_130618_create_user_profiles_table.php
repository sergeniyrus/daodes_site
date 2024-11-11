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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('wallet_address')->nullable();
            $table->enum('role', ['executor', 'client', 'both'])->default('client');

            // Контактные данные
            $table->string('avatar_url')->nullable();
            $table->string('nickname')->nullable();
            $table->string('timezone')->nullable();
            $table->json('languages')->nullable();

            // Профессиональные и личные данные
            $table->date('birth_date')->nullable();
            $table->text('education')->nullable();
            $table->text('resume')->nullable();
            $table->json('portfolio')->nullable();
            $table->text('specialization')->nullable();

            // Репутация и рейтинг
            $table->decimal('rating', 5, 2)->default(0);
            $table->decimal('trust_level', 5, 2)->default(0);
            $table->integer('sbt_tokens')->default(0);

            // Задания и рекомендации
            $table->integer('tasks_completed')->default(0);
            $table->integer('tasks_failed')->default(0);
            $table->json('recommendations')->nullable();

            // Активность и достижения
            $table->json('activity_log')->nullable();
            $table->json('achievements')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}

