<?php

namespace Database\Seeders;

use App\Models\Ads\Kvartira;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KvartiraTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kvartira::factory()->count(60)->create();
    }

    //Чтоб запустить
//php artisan db:seed --class=KvartiraTableSeeder
//php artisan make:factory
//php artisan make:seeder
}
