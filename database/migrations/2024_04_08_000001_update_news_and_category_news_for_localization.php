<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration {
    public function up(): void
    {
        // --- Обновление таблицы news ---
        Schema::table('news', function (Blueprint $table) {
            // Добавляем title_en, если нет
            if (!Schema::hasColumn('news', 'title_en')) {
                $table->string('title_en', 255)->nullable()->after('title');
            }

            // Добавляем content_en, если нет
            if (!Schema::hasColumn('news', 'content_en')) {
                $table->text('content_en')->nullable()->after('content');
            }

           
        });

        // --- Добавление name_en в category_news ---
        Schema::table('category_news', function (Blueprint $table) {
            if (!Schema::hasColumn('category_news', 'name_en')) {
                $table->string('name_en', 255)->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        // --- Откат для news ---
        Schema::table('news', function (Blueprint $table) {
            if (Schema::hasColumn('news', 'title_en')) {
                $table->dropColumn('title_en');
            }

            if (Schema::hasColumn('news', 'content_en')) {
                $table->dropColumn('content_en');
            }

            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')
                ->nullable(false)
                ->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // --- Откат для category_news ---
        Schema::table('category_news', function (Blueprint $table) {
            if (Schema::hasColumn('category_news', 'name_en')) {
                $table->dropColumn('name_en');
            }
        });
    }
};
