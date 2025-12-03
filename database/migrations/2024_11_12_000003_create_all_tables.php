<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        //  3 Таблицы категорий
        if (!Schema::hasTable('category_news')) {
            Schema::create('category_news', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->unique();       // название по-русски
                $table->string('name_en', 255)->nullable();  // перевод
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('category_tasks')) {
            Schema::create('category_tasks', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->unique();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('category_offers')) {
            Schema::create('category_offers', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->unique();
                $table->timestamps();
            });
        }
        // Таблица кошельки
        if (!Schema::hasTable('wallets')) {
            Schema::create('wallets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('balance', 18, 8)->default(100)->unsigned();
                $table->timestamps();
            });
        }
        // Таблица предложений
        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->text('content');
                $table->string('img')->nullable();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->index()
                    ->name('fk_offers_user_id');
                $table->foreignId('category_id')->constrained('category_offers')->onDelete('cascade')->index()->name('fk_offers_category_id');
                $table->unsignedInteger('views')->default(0);
                $table->string('state', 50)->default('0')->nullable();
                $table->string('method', 50)->default('0')->nullable();
                $table->string('budget', 100)->default('0')->nullable();
                $table->string('coin', 10)->default('0')->nullable();

                // Удалены значения по умолчанию '0' для полей date
                $table->date('control')->nullable();
                $table->date('finish')->nullable();

                $table->dateTime('start_vote')->nullable();
                $table->string('pdf_ipfs_cid', 255)->nullable();
                $table->timestamps();
            });
        }
        // Одобрение голосования
        if (!Schema::hasTable('discussions')) {
            Schema::create('discussions', function (Blueprint $table) {
                $table->id();
                // Внешний ключ для связи с таблицей offers
                $table->foreignId('offer_id')
                    ->constrained('offers')  // Связь с таблицей offers
                    ->onDelete('cascade');   // При удалении предложения удаляются все его обсуждения    
                // Внешний ключ для связи с таблицей users
                $table->foreignId('user_id')
                    ->constrained('users')   // Связь с таблицей users
                    ->onDelete('cascade');   // При удалении пользователя удаляются все его обсуждения    
                // Поле для голосования (true - готов к голосованию, false - нет)
                $table->boolean('vote')->default(false) // По умолчанию false (не готов к голосованию)
                    ->comment('true (1) for ready to vote, false (0) otherwise');
                // Временные метки
                $table->timestamps();
                // Уникальный индекс на сочетание offer_id и user_id
                $table->unique(['offer_id', 'user_id']);  // Чтобы один пользователь мог создать только одно обсуждение на одно предложение

            });
        }
        //  Таблицы голосования за предложение
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
        // comments_offers
        if (!Schema::hasTable('comments_offers')) {
            Schema::create('comments_offers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('offer_id')->constrained('offers')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('text')->nullable();
                $table->timestamps();
            });
        }
        // Таблица новостей
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);               // по-русски
                $table->string('title_en', 255)->nullable(); // перевод
                $table->text('content');                    // по-русски
                $table->text('content_en')->nullable();     // перевод
                $table->string('img')->nullable();
                $table->foreignId('category_id')->constrained('category_news')->onDelete('cascade');
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->onDelete('set null');
                $table->unsignedInteger('views')->default(0);
                $table->timestamps();
            });
        }

        // комментарии к ним
        if (!Schema::hasTable('comments_news')) {
            Schema::create('comments_news', function (Blueprint $table) {
                $table->id();
                $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('text')->nullable();
                $table->timestamps();
            });
        }

        // таблица tasks 
        if (!Schema::hasTable('tasks')) {
            // Создание таблицы tasks со всеми необходимыми полями
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
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
                $table->timestamp('end_time')->nullable();
                $table->timestamp('completion_time')->nullable(); // Время завершения задачи

                $table->boolean('completed')->default(false);  // Статус задачи: завершена или нет                
                $table->integer('rating')->nullable(); // Рейтинг задачи
                //$table->timestamp('completed_at')->nullable(); // Время завершения задачи

                // Временные метки
                $table->timestamps();
            });
        }
        // предложения фрилансеров
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

        // таблица task_votes - лайк дизлайк
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

        // Таблица seed
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

        // Таблица history_pays
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
        // проверка офера на спам
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
        // запись о спамерах
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
        // Профиль пользователя
        if (!Schema::hasTable('user_profiles')) {
            Schema::create('user_profiles', function (Blueprint $table) {
                $table->id();
                // Внешний ключ для связи с таблицей users
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                // Основные данные профиля
                $table->enum('role', ['executor', 'customer', 'both'])->default('customer');
                // Контактные данные
                $table->string('avatar_url')->nullable();
                $table->string('nickname')->nullable()->unique(); // Индекс для быстрого поиска по нику
                $table->bigInteger('telegram_chat_id')->nullable()->unique();
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

    public function down(): void
    {
        if (Schema::hasTable('history_pays')) Schema::dropIfExists('history_pays');
        if (Schema::hasTable('seed')) Schema::dropIfExists('seed');
        if (Schema::hasTable('comments_news')) Schema::dropIfExists('comments_news');
        if (Schema::hasTable('news')) Schema::dropIfExists('news');
        if (Schema::hasTable('comments_offers')) Schema::dropIfExists('comments_offers');
        if (Schema::hasTable('offer_votes')) Schema::dropIfExists('offer_votes');
        if (Schema::hasTable('discussions')) Schema::dropIfExists('discussions');
        if (Schema::hasTable('task_votes')) Schema::dropIfExists('task_votes');
        if (Schema::hasTable('tasks')) Schema::dropIfExists('tasks');
        if (Schema::hasTable('bids')) Schema::dropIfExists('bids');
        if (Schema::hasTable('offers')) Schema::dropIfExists('offers');
        if (Schema::hasTable('wallets')) Schema::dropIfExists('wallets');
        if (Schema::hasTable('user_profiles')) Schema::dropIfExists('user_profiles');
        if (Schema::hasTable('spam')) Schema::dropIfExists('spam');
        if (Schema::hasTable('spammers')) Schema::dropIfExists('spammers');
        if (Schema::hasTable('category_offers')) Schema::dropIfExists('category_offers');
        if (Schema::hasTable('category_tasks')) Schema::dropIfExists('category_tasks');
        if (Schema::hasTable('category_news')) Schema::dropIfExists('category_news');
    }
};
