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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id'); // ID пользователя, который поставил лайк
            $table->unsignedBigInteger('likeable_id'); // ID связанной записи (например, объявления)
            $table->string('likeable_type'); // Тип связанной записи (например, тип модели объявления)
            $table->timestamps();

            // Добавьте индексы для улучшения производительности
            $table->index('author_id');
            $table->index(['likeable_id', 'likeable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
