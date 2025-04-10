<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCategoryOffersAndOffersTablesForLocalization extends Migration
{
    public function up()
    {
        

        // Обновление таблицы offers
        if (Schema::hasTable('offers')) {
            Schema::table('offers', function (Blueprint $table) {
                
                // Переименовываем поля title и content
                $table->renameColumn('title', 'title_ru');
                $table->string('title_en', 255)->nullable()->after('title_ru'); // Добавление поля title_en

                $table->renameColumn('content', 'content_ru');
                $table->text('content_en')->nullable()->after('content_ru'); // Добавление поля content_en
            });
        }
    }

    public function down()
    {
        // Откат изменений для таблицы category_offers
        if (Schema::hasTable('category_offers')) {
            Schema::table('category_offers', function (Blueprint $table) {
                $table->dropColumn('name_en'); // Удаляем поле name_en
                $table->renameColumn('name_ru', 'name'); // Переименовываем обратно name_ru в name
            });
        }

        // Откат изменений для таблицы offers
        if (Schema::hasTable('offers')) {
            Schema::table('offers', function (Blueprint $table) {
                // Восстанавливаем внешний ключ для привязки пользователя (автора)
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->index()
                    ->name('fk_offers_user_id');

                // Восстанавливаем поля title и content
                $table->renameColumn('title_ru', 'title');
                $table->renameColumn('content_ru', 'content');
                $table->dropColumn('title_en'); // Удаляем поле title_en
                $table->dropColumn('content_en'); // Удаляем поле content_en
            });
        }
    }
}
