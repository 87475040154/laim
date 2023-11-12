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
        Schema::create('bue_ads', function (Blueprint $table) {
            $table->id();
            $table->integer('author_id');
            $table->integer('ads_id');
            $table->string('table_name');
            $table->double('summ');
            $table->string('bue_ads_type');
            $table->string('result')->default('В ожидании');
            $table->timestamps();

            // Добавление индексов
            $table->index('author_id'); // Индекс на поле author_id
            $table->index('ads_id');
            $table->index('table_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bue_ads');
    }
};
