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
        // 1. Таблицы категорий
        if (!Schema::hasTable('category_news')) {
            Schema::create('category_news', function (Blueprint $table) {
                $table->id();
                $table->string('category_name', 255)->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('category_tasks')) {
            Schema::create('category_tasks', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('category_offers')) {
            Schema::create('category_offers', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // 2. Основные таблицы пользователей, кошельков и предложений
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                //$table->string('email')->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('balance', 18, 8)->default(100)->unsigned();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->decimal('price', 10, 2);
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('category_id')->constrained('category_offers')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 3. Таблицы offer_votes и comments_offers
        if (!Schema::hasTable('offer_votes')) {
            Schema::create('offer_votes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->integer('vote')->comment('1 = За, 2 = Против, 3 = Воздержался');
                $table->unique(['offer_id', 'user_id']);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('comments_offers')) {
            Schema::create('comments_offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('text')->nullable();
                $table->timestamps();
            });
        }

        // 4. Таблица новостей и комментарии к ним
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->text('content');
                $table->string('img')->nullable();
                $table->foreignId('category_id')->constrained('category_news')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->unsignedInteger('views')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('comments_news')) {
            Schema::create('comments_news', function (Blueprint $table) {
                $table->id();
                $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('text')->nullable();
                $table->timestamps();
            });
        }

        // 5. Таблица заявок заданий и лайки дизлайки
        

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
                $table->foreignId('category_id')->nullable()->constrained('category_tasks')->onDelete('cascade'); // Связь с таблицей категорий

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

        if (!Schema::hasTable('bids')) {
            Schema::create('bids', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('price', 10, 2);
                $table->date('deadline')->nullable();
                $table->text('comment')->nullable();
                $table->integer('days')->default(0);
                $table->integer('hours')->default(0);
                $table->timestamps();
            });
        }

        // Создание таблицы task_votes
        if (!Schema::hasTable('task_votes')) {
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

        // 6. Таблица seed
        if (!Schema::hasTable('seed')) {
            Schema::create('seed', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                for ($i = 0; $i < 24; $i++) {
                    $table->string("word$i")->nullable();
                }
                $table->timestamps();
            });
        }

        // 7. Таблица history_pays
        if (!Schema::hasTable('history_pays')) {
            Schema::create('history_pays', function (Blueprint $table) {
                $table->id();
                $table->foreignId('from_wallet_id')->constrained('wallets')->onDelete('cascade')->index('idx_history_pays_from_wallet');
                $table->foreignId('to_wallet_id')->constrained('wallets')->onDelete('cascade')->index('idx_history_pays_to_wallet');
                $table->decimal('amount', 18, 8)->comment('The amount of money transferred between wallets.');
                $table->decimal('fee', 18, 8)->comment('The transaction fee applied to the transfer.');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('spam')) {
            Schema::create('spam', function (Blueprint $table) {
                $table->id();

                // Внешние ключи для предложения и пользователя
                $table->foreignId('offer_id')
                    ->constrained('offers')  // Связь с таблицей offers
                    ->onDelete('cascade');   // При удалении предложения удаляются все его отметки как спам

                $table->foreignId('user_id')
                    ->constrained('users')   // Связь с таблицей users
                    ->onDelete('cascade');   // При удалении пользователя удаляются все его отметки как спам

                // Голосование за спам (0 - не спам, 1 - спам)
                $table->boolean('vote')    // Можно оставить boolean или tinyInteger(1)
                    ->comment('1 = Спам, 0 = Не спам');

                // Временные метки
                $table->timestamps();

                // Уникальный индекс на сочетание id_offer и id_user, чтобы каждый пользователь мог отметить только один раз
                $table->unique(['offer_id', 'user_id']);
            });
        }

        if (!Schema::hasTable('spammers')) {
            Schema::create('spammers', function (Blueprint $table) {
                $table->id();

                // Внешний ключ для пользователя, который помечен как спамер
                $table->foreignId('user_id')
                    ->constrained('users')   // Связь с таблицей users
                    ->onDelete('cascade');   // При удалении пользователя удаляются все его записи как спамера

                // Количество штрафных очков за спам (будет увеличиваться с каждым новым инцидентом)
                $table->integer('penalty')->default(1); // Начальный штраф - 1

                // Временные метки
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();

                // Внешний ключ для связи с таблицей users
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                // Основные данные профиля
                $table->enum('role', ['executor', 'customer', 'both'])->default('customer');

                // Контактные данные
                $table->string('avatar_url')->nullable();
                $table->string('nickname')->nullable(); // Индекс для быстрого поиска по нику
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
                $table->text('recommendations')->nullable();

                // Активность и достижения
                $table->text('activity_log')->nullable();
                $table->text('achievements')->nullable();

                // Временные метки
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pays');
        Schema::dropIfExists('seed');
        Schema::dropIfExists('bids');
        Schema::dropIfExists('comments_news');
        Schema::dropIfExists('news');
        Schema::dropIfExists('comments_offers');
        Schema::dropIfExists('offer_votes');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('users');
        Schema::dropIfExists('category_offers');
        Schema::dropIfExists('category_tasks');
        Schema::dropIfExists('category_news');
        Schema::dropIfExists('task_votes');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('spam');
        Schema::dropIfExists('spammers');
    }
};
