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
        Schema::create('prochayas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('author_id');
            $table->string('tip_sdelki');


            $table->string('tip_obekta')->default('Прочая');
            $table->string('table_name')->default('Prochaya');

            $table->string('zagolovok');
            $table->string('deistvuushii_biznes');

            $table->double('cena');
            $table->string('cena_tip');

            $table->string('sfera_deyatelnosti');
            $table->integer('ploshad_obshaya');
            $table->integer('ploshad_uchastka')->nullable();;
            $table->string('tip_stroeniya');
            $table->integer('god_postroiki');


            $table->string('oblast');
            $table->string('gorod');
            $table->string('raion')->nullable();
            $table->double('lat');
            $table->double('lon');
            $table->string('ulica');
            $table->string('nomer_doma');

            $table->json('images')->nullable();

            $table->string('sostoyanie')->nullable();

            $table->json('raspolojenie')->nullable();
            $table->json('kommunikacii')->nullable();

            $table->text('text_obyavleniya');
            $table->string('name');
            $table->string('tel');
            $table->tinyInteger('whatsapp_tel')->default(0);
            $table->string('tel_2')->nullable();
            $table->tinyInteger('whatsapp_tel_2')->default(0);



            //Управление
            $table->string('control')->default('Активно'); //Активно, не активно
            $table->json('complain')->nullable();

            //Статистика - Просмотры лайки
            $table->integer('view')->default(0);
            $table->integer('view_tel')->default(0);
            $table->integer('count_like')->default(0);

            //Рекламные услуги
            $table->boolean('srochno_torg')->default(false); //Добавить метку срочно - Торг
            $table->dateTime('bue_ads')->default('2020-12-12 12:12:12'); // Указать что на данное объявление куплена реклама, для дальнейшей сортировки при получении объявлений
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
            $table->index('deistvuushii_biznes');
            $table->index('cena');
            $table->index('cena_tip');
            $table->index('sfera_deyatelnosti');
            $table->index('ploshad_obshaya');
            $table->index('raion');
            $table->index('sostoyanie');
            $table->index('bue_ads');
            $table->index('top');
            $table->index('top_8');
            $table->index('top_x7');
            $table->index('top_x30');
            $table->index('updated_at');

            // Создание составного индекса
            $table->index(['bue_ads', 'updated_at']);
            $table->index(['oblast', 'gorod']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prochayas');
    }
};
