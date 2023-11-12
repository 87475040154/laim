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
        Schema::create('ads_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable(); // ID пользователя, который поставил лайк
            $table->string('author_ip');
            $table->unsignedBigInteger('viewable_id'); // ID связанной записи (например, объявления)
            $table->string('viewable_type'); // Тип связанной записи (например, тип модели объявления)
            $table->timestamps();

            // Добавьте индексы для улучшения производительности
            $table->index('author_id');
            $table->index('author_ip');
            $table->index(['viewable_id', 'viewable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_views');
    }
};
