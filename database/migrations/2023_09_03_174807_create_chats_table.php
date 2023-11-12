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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_id');
            $table->integer('recipient_id');
            $table->string('message')->nullable();
            $table->boolean('delete_sender')->default(false);
            $table->boolean('delete_recipient')->default(false);
            $table->boolean('open_recipient')->default(false);
            $table->dateTime('open_date')->nullable();
            $table->boolean('chat')->default(true);

            //Данные объявления
            $table->integer('author_id')->nullable();
            $table->string('zagolovok')->nullable();
            $table->string('tip_sdelki')->nullable();
            $table->string('tip_obekta')->nullable();
            $table->string('table_name')->nullable();
            $table->integer('ads_id')->nullable();
            $table->integer('kolichestvo_komnat')->nullable();
            $table->integer('ploshad_obshaya')->nullable();
            $table->integer('etag')->nullable();
            $table->integer('etagnost')->nullable();
            $table->integer('cena')->nullable();
            $table->string('cena_tip')->nullable();
            $table->string('period_arendi')->nullable();
            $table->string('images')->default('no-image');
            $table->string('gorod')->nullable();
            $table->string('ulica')->nullable();
            $table->string('nomer_doma')->nullable();
            $table->string('ownerOrRealtor')->nullable();
            $table->string('control')->nullable();
            $table->dateTime('ads_created')->nullable();

            $table->timestamps();

            // Добавление индексов
            $table->index('sender_id');
            $table->index('recipient_id');
            $table->index('delete_sender');
            $table->index('delete_recipient');
            $table->index('open_recipient');
            $table->index('chat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
