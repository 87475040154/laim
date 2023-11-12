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
        Schema::create('arhive_ads', function (Blueprint $table) {
            $table->id();

            $table->integer('ads_id');

            $table->integer('author_id')->nullable();

            $table->string('tip_obekta')->nullable();
            $table->string('table_name')->nullable();

            $table->string('sfera_deyatelnosti')->nullable();
            $table->string('zagolovok')->nullable();
            $table->string('tip_sdelki')->nullable();
            $table->string('tip_ofisa')->nullable();
            $table->string('deistvuushii_bisnes')->nullable();
            $table->string('period_arendi')->nullable();
            $table->integer('kolichestvo_komnat')->nullable();
            $table->double('cena');
            $table->string('cena_tip')->nullable();
            $table->string('mestopolojenie')->nullable();
            $table->integer('etag')->nullable();
            $table->integer('etagnost')->nullable();
            $table->integer('ploshad_obshaya')->nullable();
            $table->integer('ploshad_kuhni')->nullable();
            $table->integer('ploshad_uchastka')->nullable();
            $table->string('visota_potolkov')->nullable();
            $table->string('tip_stroeniya')->nullable();

            $table->string('oblast')->nullable();
            $table->string('gorod')->nullable();
            $table->string('raion')->nullable();
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();
            $table->string('ulica')->nullable();
            $table->string('nomer_doma')->nullable();

            $table->string('images')->nullable();

            $table->string('sostoyanie')->nullable();
            $table->string('mebel')->nullable();
            $table->string('internet')->nullable();
            $table->string('dush')->nullable();

            $table->string('sanuzel')->nullable();
            $table->string('otdelniy_vhod')->nullable();
            $table->string('balkon')->nullable();
            $table->string('kanalizaciya')->nullable();
            $table->string('voda')->nullable();
            $table->string('elektrichestvo')->nullable();
            $table->string('otoplenie')->nullable();
            $table->string('gaz')->nullable();
            $table->string('pol')->nullable();
            $table->string('parkovka')->nullable();

            $table->string('ploshad_proizvodstvennih_pomesheniy')->nullable();
            $table->string('visota_proizvodstvennih_pomesheniy')->nullable();
            $table->string('ploshad_skladskih_pomesheniy')->nullable();
            $table->string('visota_skladskih_pomesheniy')->nullable();
            $table->string('ploshad_ofisnih_pomesheniy')->nullable();

            $table->text('mebel_arr')->nullable();
            $table->text('raznoe')->nullable();
            $table->text('bezopasnost')->nullable();
            $table->string('raspolojenie')->nullable();
            $table->text('kommunikacii')->nullable();



            $table->text('text_obyavleniya')->nullable();
            $table->string('ownerOrRealtor')->nullable();
            $table->string('name')->nullable();
            $table->string('tel')->nullable();
            $table->string('tel2')->nullable();

            //Управление
            $table->string('control')->default('В архиве'); //Активно, не активно
            $table->dateTime('delete')->nullable();

            //Статистика - Просмотры лайки
            $table->integer('view')->default(0);
            $table->integer('countLike')->default(0);

            $table->timestamps();

            // Добавление индексов
            $table->index('author_id'); // Индекс на поле author_id
            $table->index('tip_obekta');
            $table->index('table_name');
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
            $table->index('delete');
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
        Schema::dropIfExists('arhive_ads');
    }
};
