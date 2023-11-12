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
        Schema::create('doms', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author_id');

            $table->string('zagolovok');
            $table->string('tip_obekta');
            $table->string('table_name')->default('Dom');
            $table->string('period_arendi');
            $table->integer('kolichestvo_komnat');
            $table->double('cena');
            $table->integer('etagnost');

            $table->integer('ploshad_obshaya');
            $table->integer('ploshad_kuhni')->nullable();
            $table->integer('ploshad_uchastka');

            $table->string('tip_stroeniya');

            $table->string('oblast');
            $table->string('gorod');
            $table->string('raion')->nullable();
            $table->double('lat');
            $table->double('lon');
            $table->string('ulica');
            $table->string('nomer_doma');

            $table->string('images')->default('no-image');

            $table->string('sostoyanie')->nullable();
            $table->string('mebel')->nullable();
            $table->string('internet')->nullable();

            $table->string('sanuzel')->nullable();
            $table->string('kanalizaciya')->nullable();
            $table->string('voda')->nullable();
            $table->string('elektrichestvo')->nullable();
            $table->string('otoplenie')->nullable();
            $table->string('gaz')->nullable();
            $table->string('pol')->nullable();
            $table->string('parkovka')->nullable();

            $table->text('mebel_arr')->nullable();

            $table->text('raznoe')->nullable();

            $table->text('bezopasnost')->nullable();



            $table->text('text_obyavleniya');
            $table->string('ownerOrRealtor');
            $table->string('name');
            $table->string('tel');
            $table->string('tel2')->nullable();


            //Управление
            $table->string('control')->default('Активно'); //Активно, не активно
            $table->text('complain')->nullable();
            $table->dateTime('delete')->nullable();

            //Статистика - Просмотры лайки
            $table->integer('view')->default(0);
            $table->integer('countLike')->default(0);

            //Рекламные услуги
            $table->boolean('srochno_torg')->default(false); //Добавить метку срочно - Торг
            $table->dateTime('bueAds')->nullable(); // Указать что на данное объявление куплена реклама, для дальнейшей сортировки при получении объявлений
            $table->dateTime('top')->nullable(); // Отправить в ТОП на 24 часа,
            $table->dateTime('top_8')->nullable(); // Отправить в ТОП на 24 часа и 8 поднятий в верх Топа,
            $table->dateTime('goryachie')->nullable(); // Отправить в горячие на 24 часа,
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
            $table->index('tip_obekta');
            $table->index('period_arendi');
            $table->index('kolichestvo_komnat');
            $table->index('cena');
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
            $table->index('goryachie');
            $table->index('top_x7');
            $table->index('top_x30');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doms');
    }
};
