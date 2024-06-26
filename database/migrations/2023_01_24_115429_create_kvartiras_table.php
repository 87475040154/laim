<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kvartiras', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author_id');

            $table->string('tip_obekta')->default('Квартира');
            $table->string('table_name')->default('Kvartira');

            $table->string('zagolovok');
            $table->string('period_arendi');
            $table->integer('kolichestvo_komnat');
            $table->double('cena');
            $table->integer('etag');
            $table->integer('etagnost');
            $table->integer('ploshad_obshaya');
            $table->integer('ploshad_kuhni')->nullable();
            $table->string('tip_stroeniya');

            $table->string('oblast');
            $table->string('gorod');
            $table->string('raion')->nullable();
            $table->double('lat');
            $table->double('lon');
            $table->string('ulica');
            $table->string('nomer_doma');

            $table->json('images')->nullable();

            $table->string('sostoyanie')->nullable();
            $table->string('mebel')->nullable();

            $table->string('sanuzel')->nullable();
            $table->string('balkon')->nullable();

            $table->json('mebel_arr')->nullable();
            $table->json('raznoe')->nullable();
            $table->json('bezopasnost')->nullable();

            $table->text('text_obyavleniya');
            $table->string('ownerOrRealtor');
            $table->string('name');
            $table->string('tel');
            $table->string('tel2')->nullable();


            //Управление
            $table->string('control')->default('Активно'); //Активно, не активно
            $table->json('complain')->nullable();

            //Статистика - Просмотры лайки
            $table->integer('view')->default(0);
            $table->integer('viewTel')->default(0);
            $table->integer('countLike')->default(0);

            //Рекламные услуги
            $table->boolean('srochno_torg')->default(false); //Добавить метку срочно - Торг
            $table->dateTime('bueAds')->default('2020-12-12 12:12:12'); // Указать что на данное объявление куплена реклама, для дальнейшей сортировки при получении объявлений
            $table->dateTime('top')->nullable(); // Отправить в ТОП на 24 часа,
            $table->dateTime('top_8')->nullable(); // Отправить в ТОП на 24 часа и 8 поднятий в верх Топа,
            $table->dateTime('top_x7')->nullable(); // Отпраить в топ на 7 дней -  top x7 ,
            $table->dateTime('top_x30')->nullable(); // Отпраить в топ на 30 дней - top x30,

            $table->timestamps();

            //Для оптимизации
            $table->foreign('author_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // Внешний ключ на таблицу users

            // Добавление индексов
            $table->index('author_id'); // Индекс на поле author_id
            $table->index('period_arendi');
            $table->index('kolichestvo_komnat');
            $table->index('cena');
            $table->index('etag');
            $table->index('etagnost');
            $table->index('ploshad_obshaya');
            $table->index('oblast');
            $table->index('gorod');
            $table->index('raion');
            $table->index('ownerOrRealtor');
            $table->index('sostoyanie');
            $table->index('mebel');
            $table->index('bueAds');
            $table->index('top');
            $table->index('top_8');
            $table->index('top_x7');
            $table->index('top_x30');
            $table->index('updated_at');
            // Создание составного индекса
            $table->index(['bueAds', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kvartiras');
    }
};
