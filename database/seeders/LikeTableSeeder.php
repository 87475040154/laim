<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Like::factory()->count(60000)->create();
    }
    //Чтоб запустить
//php artisan db:seed --class=LikeTableSeeder
//php artisan make:factory
//php artisan make:seeder
}

