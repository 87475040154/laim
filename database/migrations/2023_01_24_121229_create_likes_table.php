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

            // Кто лайкнул
            $table->unsignedBigInteger('author_id');

            // Что лайкнули
            $table->unsignedBigInteger('likeable_id');
            $table->string('likeable_type', 80);

            $table->timestamps();

            // 🔹 Индексы для ускорения выборок и фильтрации
            $table->index(['author_id', 'likeable_type', 'likeable_id'], 'idx_likes_user_type_id');
            $table->index(['likeable_type', 'likeable_id'], 'idx_likes_target');
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
