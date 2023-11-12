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
        Schema::create('k_z_locations', function (Blueprint $table) {
            $table->id();

            $table->integer('parent_id')->nullable();

            $table->string('oblast')->nullable();
            $table->string('gorod')->nullable();
            $table->string('raion')->nullable();
            $table->double('lat');
            $table->double('lon');

            // Добавление индексов
            $table->index('oblast');
            $table->index('gorod');
            $table->index('raion');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('k_z_locations');
    }
};
