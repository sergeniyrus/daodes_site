<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAuthorToUserIdInNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('author'); // добавляем новый столбец user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // добавляем внешний ключ

            // если необходимо, скопируем данные из столбца author в user_id
            DB::statement('UPDATE news SET user_id = author');
            
            $table->dropColumn('author'); // удаляем старый столбец author
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('author')->after('user_id'); // добавляем обратно столбец author
            $table->dropForeign(['user_id']); // удаляем внешний ключ
            $table->dropColumn('user_id'); // удаляем столбец user_id
        });
    }
}
